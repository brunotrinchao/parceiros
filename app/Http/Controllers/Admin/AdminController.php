<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminController extends Controller
{
    function __construct(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add('IMÓVEIS');
            $event->menu->add(
                [
                    'text'    => 'Indicação',
                    'icon'    => 'pencil-square',
                    'submenu' => [
                        [
                            'text'    => 'Proprietário',
                            'url'     => '#',
                            'icon'     => 'circle',
                            'submenu' => [
                                [
                                    'text' => 'Comprar',
                                    'url'  => 'admin/imoveis/indicacao/proprietario/comprar',
                                    'icon'     => 'angle-double-right'
                                ],
                                [
                                    'text' => 'Vender',
                                    'url'  => '#',
                                    'icon'     => 'angle-double-right'
                                ],
                                [
                                    'text' => 'Alugar',
                                    'url'  => '#',
                                    'icon'     => 'angle-double-right'
                                ]
                            ],
                        ],
                        [
                            'text'    => 'Interessado',
                            'url'     => '#',
                            'icon'     => 'circle',
                            'submenu' => [
                                [
                                    'text' => 'Comprar',
                                    'url'  => '#',
                                    'icon'     => 'angle-double-right'
                                ],
                                [
                                    'text' => 'Vender',
                                    'url'  => '#',
                                    'icon'     => 'angle-double-right'
                                ],
                                [
                                    'text' => 'Alugar',
                                    'url'  => '#',
                                    'icon'     => 'angle-double-right'
                                ]
                            ],
                        ]
                    ]
                ],
                [
                    'text'    => 'Relatórios',
                    'icon'    => 'pie-chart',
                    'submenu' => [
                        [
                        'text'    => 'Mensal',
                        'url'     => '#',
                        'icon'     => 'circle'
                        ]
                    ]
                ],
                [
                    'text'    => 'Material promocional',
                    'icon'    => 'archive',
                    'url'    => '#',
                ],
                [
                    'text'    => 'Ajuda',
                    'icon'    => 'life-ring',
                    'url'    => '#',
                ],
                [
                    'text'    => 'Administração',
                    'icon'    => 'cog',
                ]
            );
            // $event->menu->add(
            //     [
            //     'text' => 'Indicação',
            //     'url' => 'admin/imoveis/blog',
            //     'icon' => 'file',
            //     'submenu' => [
            //             'text' => 'Proprietário',
            //             [
            //                 'text' => 'Comprar',
            //                 'url'  => '#',
            //             ],
            //             [
            //                 'text' => 'Vender',
            //                 'url'  => '#',
            //             ],
            //             [
            //                 'text' => 'Alugar',
            //                 'url'  => '#',
            //             ]
            //         ],
            //         [
            //             'text' => 'Interessado',
            //             [
            //                 'text' => 'Comprar',
            //                 'url'  => '#',
            //             ],
            //             [
            //                 'text' => 'Vender',
            //                 'url'  => '#',
            //             ],
            //             [
            //                 'text' => 'Alugar',
            //                 'url'  => '#',
            //             ]
            //         ]
            //     ]);
        });

    }

}