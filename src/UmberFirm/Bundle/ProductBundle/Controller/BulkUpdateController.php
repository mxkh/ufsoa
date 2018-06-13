<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ProductBundle\Entity\BulkUpdate;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class BulkUpdateController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("bulk_update")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class BulkUpdateController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Bulk Update Product detail from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $bulkUpdate = new BulkUpdate();
        $data = array_merge($request->files->all(), $request->request->all());
        $form = $this->createFormBuilder($bulkUpdate)
            ->add('file', FileType::class, ['required' => true])
            ->getForm();
        $form->submit($data);
    
        if (true === $form->isValid() && true === (($file = $form->get('file')->getData()) instanceof UploadedFile)) {
            $csvUpdater = $this->container->get('product.csv.updater');
            $csvUpdater->getCsvStructureFormBuilder()->add('code', TextType::class, ['required' => true]);
            $csvUpdater->getCsvStructureFormBuilder()->add('name', TextType::class, ['required' => true]);
            $csvUpdater->getCsvStructureFormBuilder()->add('description', TextType::class, ['required' => true]);
            $csvUpdater->getCsvStructureFormBuilder()->add('short_description', TextType::class, ['required' => true]);
            
            if(true === $csvUpdater->process($file, $request->getLocale())) {
                return $this->view(['number_updated_rows' => $csvUpdater->getNumberUpdatedRows()], Response::HTTP_CREATED);
            }
            
            return $this->view($csvUpdater->getErrors(), Response::HTTP_BAD_REQUEST);
        }
        
        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
