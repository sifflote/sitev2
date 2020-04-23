<?php
// src/Controller/HomeController.php
namespace App\Controller;

use App\Controller\HTML\AvatarController;
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

        return $this->render('home.html.twig');
    }

    /**
    // * @Route("/admin", name="admin")
    // */
    /*public function admin()
    {
        return $this->render('admin/index.html.twig');
    }*/
}