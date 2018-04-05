<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AdminController extends Controller
{
    function __construct(Request $request, Dispatcher $events)
    {
        $this->middleware('auth');

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add('IMÓVEIS');
            $event->menu->add(
                [
                    'text'    => 'Dashboard',
                    'url'     => 'admin',
                    'icon'     => 'dashboard'
                ],
                [
                    'text'    => 'Indicação',
                    'icon'    => 'pencil-square',
                    'submenu' => [
                        [
                            'text'    => 'Comprar',
                            'url'     => 'admin/imoveis/indicacao/comprar',
                            'icon'     => 'circle'
                        ],
                        [
                            'text'    => 'Proprietário',
                            'url'     => '#',
                            'icon'     => 'circle',
                            'submenu' => [
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
                    'url'    => 'admin/imoveis/arquivos',
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
        });

    }


}