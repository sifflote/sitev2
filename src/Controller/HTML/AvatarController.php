<?php


namespace App\Controller\HTML;


use Jdenticon\Identicon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvatarController extends AbstractController
{

    public function iconAvatar(string $avatar): string
    {
        $icon = new Identicon(array(
            'value' => '$avatar',
            'size' => 50,
            'style' => array(
                'backgroundColor' => '#86444400',
                'colorLightness' => array(0.32, 0.87),
                'grayscaleLightness' => array(0.22, 0.90),
                'colorSaturation' => 0.50,
                'grayscaleSaturation' => 0.28,
            )));
        $avatar = $icon->displayImage('svg');
        return $avatar;
    }
}