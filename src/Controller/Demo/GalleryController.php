<?php

namespace App\Controller\Demo;

use App\Entity\Base\Asset;
use App\Entity\Demo\GalleryItem;
use App\FormType\Form\Demo\GalleryItemForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController {
    private const PLACEHOLDER_COUNT = 15;
    private const IMG_W_UP_BOUND = 600;
    private const IMG_W_LOW_BOUND = 300;
    private const IMG_H_UP_BOUND = 600;
    private const IMG_H_LOW_BOUND = 300;
    /**
     * @Route("/gallery", name="gallery_index")
     */
    public function index() {
        $data = $this->generatePlaceholder(self::PLACEHOLDER_COUNT);
        $repo = $this->getDoctrine()->getRepository(GalleryItem::class);
        foreach ($repo->findAll() as $galleryItem) {
            $asset = $galleryItem->getAssets()->first();
            $data[] = [
                "header" => $galleryItem->getHeader(),
                "content" => $galleryItem->getContent(),
                "url" => $this->generateUrl("api_asset_get_item", [
                    "id" => $asset->getId()
                ])
            ];
        }
        $form = $this->createForm(GalleryItemForm::class);
        return $this->render('render/demo/gallery/index.html.twig', [
            'data' => $data,
            'form' => $form->createView()
        ]);
    }

    private function generatePlaceholder(int $count): array {
        $rtn = [];
        $lorem =  file_get_contents("https://loripsum.net/api/10/short/plaintext");
        $arr = preg_split("/\n+/", $lorem);
        for ($i = 0; $i < $count; $i++) {
            $w = rand(self::IMG_W_LOW_BOUND, self::IMG_W_UP_BOUND);
            $h = rand(self::IMG_H_LOW_BOUND, self::IMG_H_UP_BOUND);
            $start = rand(0, 5);
            $offset = rand(1, 4);
            $text = implode("\n", array_slice($arr, $start, $offset));
            preg_match("/(\w+[\W]\w+)/", $text, $match);
            $header = $match[1] ?? "Lorem Ipsum";
            $rtn[] = [
                "url" => "https://picsum.photos/$w/$h?random",
                "header" => $header,
                "content" => $text,
            ];
        }
        return $rtn;
    }

    /**
     * @Route("/gallery/item", name="gallery_item")
     */
    public function item(Request $request) {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $id = $request->query->get("id");
        $user = $this->getUser();
        if (!empty($id)) {
            $repo = $this->getDoctrine()->getRepository(GalleryItem::class);
            $gItem = $repo->find($id);
            if (empty($gItem)) {
                throw new NotFoundHttpException("Unable to locate item");
            }
        } else {
            $gItem = new GalleryItem();
            $gItem->setOwner($user);
        }
        $form = $this->createForm(GalleryItemForm::class, $gItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $upload \Symfony\Component\HttpFoundation\File\UploadedFile */
            /* @var $gItem \App\Entity\Demo\GalleryItem */
            $gItem = $form->getData();
            $upload = $gItem->getUpload();
            if ($upload->isValid()) {
                $relPath = "\\gallery\\";
                $name = md5(uniqid()).$upload->getClientOriginalExtension();
                $file = $upload->move(realpath($this->getParameter("app_data_dir").$relPath), $name);
                $asset = new Asset();
                $asset->setType("gallery");
                $asset->setPath($name);
                $gItem->getAssets()->add($asset);
                $em = $this->getDoctrine()->getManager();
                $em->persist($asset);
                $em->persist($gItem);
                $em->flush();
                return $this->redirectToRoute("gallery_index");
            }
        }
        return $this->render("render/simple_form.html.twig", [
            "title" => "Gallery Item",
            "form" => $form->createView()
        ]);
    }
}
