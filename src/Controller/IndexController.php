<?php
// src/Controller/HomeController.php
namespace App\Controller;

use App\Entity\Auth\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class IndexController extends AbstractController
{
    /**
     * @throws Exception
     * @Route("/", name="index")
     */
    public function index()
    {
        $icon = new \Jdenticon\Identicon(array(
            'value' => 'siffli13@gmail.com-sifflote',
            'size' => 50,
            // Custom identicon style
            // https://jdenticon.com/icon-designer.html?config=864444000141321c1f57155a
            'style' => array(
            'backgroundColor' => '#86444400',
            'colorLightness' => array(0.32, 0.87),
            'grayscaleLightness' => array(0.22, 0.90),
            'colorSaturation' => 0.50,
            'grayscaleSaturation' => 0.28,
        )));
        $icon_view =$icon->displayImage('svg');
        return $this->render('home.html.twig', [
            'icon' => $icon->getImageData('svg')
        ]);
    }

    /**
    // * @Route("/admin", name="admin")
    // */
    /*public function admin()
    {
        return $this->render('admin/index.html.twig');
    }*/
}