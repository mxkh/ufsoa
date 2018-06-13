<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Customer;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerSocialDataObject;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerPassword;
use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerResetPassword;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerPasswordType;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerResetPasswordType;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerSocialType;
use UmberFirm\Bundle\CustomerBundle\Repository\CustomerRepository;
use UmberFirm\Bundle\PublicBundle\Component\Customer\PasswordTrait;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerEventInterface;
use UmberFirm\Bundle\PublicBundle\Event\Customer\CustomerResetPasswordEventInterface;
use UmberFirm\Bundle\PublicBundle\Form\CustomerLoginType;
use UmberFirm\Bundle\PublicBundle\Form\CustomerSignUpType;

/**
 * Class CustomerController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 *
 * @FOS\RouteResource("customer")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CustomerController extends BasePublicController implements ClassResourceInterface
{
    use PasswordTrait;

    /**
     * Creates a new customer from the submitted data.
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\PublicBundle\Form\CustomerSignUpType",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postSignupAction(Request $request): View
    {
        $customer = new Customer();
        $customer->setShop($this->shop);

        $form = $this->createForm(CustomerSignUpType::class, $customer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        //TODO: remove it and create SignUpValidationConstraint
        $loginManager = $this->get('umberfirm.public.component.customer.manager.login_manager');
        if (null !== $loginManager->loadCustomer($customer->getPhone(), $customer->getEmail(), $this->shop)) {
            $form->addError(new FormError('customer.user.already.exist'));
        }

        if (true === $form->isValid()) {
            // If all form data valid, hash password and set to customer
            $customer->setPassword(password_hash($customer->getPassword(), PASSWORD_BCRYPT));

            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            $this->dispatchCustomerEvent($customer, CustomerEventInterface::SIGN_UP);

            return $this->view(['success' => true], Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Authenticate customer and generate JWT token.
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerType",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     * @FOS\Post()
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postLoginAction(Request $request): View
    {
        $form = $this->createForm(CustomerLoginType::class);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (false === $form->isValid()) {
            return $this->view($form, Response::HTTP_BAD_REQUEST);
        }
        $phone = (string) $form->get('phone')->getData();
        $email = (string) $form->get('email')->getData();
        $password = (string) $form->get('password')->getData();

        $loginManager = $this->get('umberfirm.public.component.customer.manager.login_manager');
        $customer = $loginManager->loadCustomer($phone, $email, $this->shop);

        if (
            null !== $customer &&
            true === $customer->getIsConfirmed() &&
            true === $loginManager->checkCustomerPassword($customer, $password)
        ) {
            $loginManager->login($customer);
            $tokenStorage = $this->get('security.token_storage');

            return $this->view(['token' => $tokenStorage->getToken()->getCredentials()]);
        }

        $form->addError(new FormError('customer.invalid.credentials'));

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Authorization endpoint for social signup customers.
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postConfirmAction(Request $request): View
    {
        /** @var string $confirmationCode */
        $confirmationCode = $request->request->get('confirmationCode');
        $customerId = $request->request->get('customerId');

        $em = $this->getDoctrine()->getManager();

        /** @var CustomerRepository $customerRepository */
        $customerRepository = $em->getRepository(Customer::class);
        $customer = $customerRepository->findOneByConfirmationCode($confirmationCode, $customerId, $this->shop);
        if (null === $customer) {
            return $this->view(null, Response::HTTP_BAD_REQUEST);
        }

        $customer->setConfirmationCode(null);
        $customer->setIsConfirmed(true);
        $em->persist($customer);
        $em->flush();

        $this->dispatchCustomerEvent($customer, CustomerEventInterface::CONFIRM_CUSTOMER);

        return $this->view(['confirmation' => true]);
    }

    /**
     * Authorization endpoint for social signup customers.
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerSocialType",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     *
     * @param Request $request the request object
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postSignupSocialAction(Request $request): View
    {
        $socialObject = new CustomerSocialDataObject();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CustomerSocialType::class, $socialObject);
        $form->submit($data);

        if (false === $form->isValid()) {
            return $this->view($form, Response::HTTP_BAD_REQUEST);
        }

        $socialSignUp = $this->get('umberfirm.customer.social.signup');
        $customer = $socialSignUp->loadCustomer($socialObject, $this->shop);
        if (null === $customer) {
            $socialIdentity = $socialSignUp->createCustomerSocialIdentity($socialObject, $this->shop);
            $customer = $socialIdentity->getCustomer();
        }

        $socialSignUp->login($customer);
        $tokenStorage = $this->get('security.token_storage');

        return $this->view(['token' => $tokenStorage->getToken()->getCredentials()]);
    }

    /**
     * Reset password by email endpoint for customers.
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors",
     *         404 = "Returned when the resource not found"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     *
     * @FOS\Route("/customers/reset/password")
     *
     * @param Request $request the request object
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postResetPasswordAction(Request $request): View
    {
        $email = $request->get('email');
        $phone = $request->get('phone');

        $em = $this->getDoctrine()->getManager();
        $resetPasswordCodeGenerator = $this->get('umberfirm.customer.component.reset.password.code.generator');
        /** @var CustomerRepository $customerRepository */
        $customerRepository = $em->getRepository(Customer::class);

        /** @var Customer $customer */
        $customer = $customerRepository->findCustomerByPhoneOrEmail($phone, $email, $this->shop);
        if (null === $customer) {
            return $this->view([], Response::HTTP_BAD_REQUEST);
        }

        $resetPasswordCode = $this->generateResetPasswordCode($customerRepository, $resetPasswordCodeGenerator);
        $customer->setResetPasswordCode($resetPasswordCode);
        $customer->setResetPasswordCodeExpired($this->getPasswordExpiration('+1 day'));
        $em->persist($customer);
        $em->flush();

        $this->dispatchResetPasswordEvent(
            $customer,
            CustomerResetPasswordEventInterface::RESET_PASSWORD
        );

        return $this->view(['resetPasswordCode' => $resetPasswordCode], Response::HTTP_OK);
    }

    /**
     * Confirm reset password by email endpoint for customers.
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerPasswordType",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     *
     * @FOS\Route("/customers/reset/password/form")
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postGetFormByResetPasswordCodeAction(Request $request): View
    {
        $resetPasswordCode = $request->get('resetPasswordCode');
        $em = $this->getDoctrine()->getManager();
        $customerRepository = $em->getRepository(Customer::class);

        /** @var Customer $customer */
        $customer = $customerRepository->findOneByResetPasswordCode($resetPasswordCode, $this->shop);
        if (null === $customer || true === $customer->isResetPasswordCodeExpired()) {
            return $this->view([], Response::HTTP_BAD_REQUEST);
        }

        $customerPassword = new CustomerResetPassword();
        $form = $this->createForm(CustomerPasswordType::class, $customerPassword);

        return $this->view($form, Response::HTTP_OK);
    }

    /**
     * Confirm reset password by email endpoint for customers.
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerPasswordType",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     *
     * @FOS\Route("/customers/reset/password/confirm")
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postConfirmResetPasswordAction(Request $request): View
    {
        $customerResetPassword = new CustomerResetPassword();
        $form = $this->createForm(CustomerResetPasswordType::class, $customerResetPassword);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $customerRepository = $em->getRepository(Customer::class);

            $data = $form->getData();
            /** @var Customer $customer */
            $customer = $customerRepository->findOneByResetPasswordCode($data->getResetPasswordCode(), $this->shop);
            if (null !== $customer && false === $customer->isResetPasswordCodeExpired()) {
                $customer->setPassword(password_hash($data->getPassword(), PASSWORD_BCRYPT));
                $customer->setResetPasswordCode(null);
                $customer->setResetPasswordCodeExpired(null);
                $em->persist($customer);
                $em->flush();

                $this->dispatchResetPasswordEvent(
                    $customer,
                    CustomerResetPasswordEventInterface::CONFIRM_RESET_PASSWORD
                );

                return $this->view(['success' => true], Response::HTTP_OK);
            }
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Reset password endpoint for customers.
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerPasswordType",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomer"})
     *
     * @FOS\Route("/customers/change/password")
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postChangePasswordAction(Request $request): View
    {
        $em = $this->getDoctrine()->getManager();

        if (null === $this->customer) {
            return $this->view([], Response::HTTP_BAD_REQUEST);
        }

        $customerPassword = new CustomerPassword();
        $form = $this->createForm(CustomerPasswordType::class, $customerPassword);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            //change password
            $this->customer->setPassword(password_hash($customerPassword->getPassword(), PASSWORD_BCRYPT));
            $em->persist($this->customer);
            $em->flush();

            $this->dispatchResetPasswordEvent(
                $this->customer,
                CustomerResetPasswordEventInterface::CHANGE_PASSWORD
            );

            return $this->view([], Response::HTTP_OK);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Customer $customer
     * @param string $eventName
     *
     * @return void
     */
    private function dispatchResetPasswordEvent(Customer $customer, string $eventName): void
    {
        $customerResetPasswordEvent = $this
            ->get('umberfirm.public.reset.password.event.customer.factory')
            ->createCustomerResetPasswordEvent($customer);

        $this->get('event_dispatcher')->dispatch($eventName, $customerResetPasswordEvent);
    }

    /**
     * @param Customer $customer
     * @param string $eventName
     *
     * @return void
     */
    private function dispatchCustomerEvent(Customer $customer, string $eventName): void
    {
        $customerEvent = $this->get('umberfirm.public.event.customer.factory')->createCustomerEvent($customer);
        $this->get('event_dispatcher')->dispatch($eventName, $customerEvent);
    }
}
