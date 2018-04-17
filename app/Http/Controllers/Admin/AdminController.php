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
            $session = session()->get('portalparceiros');
            $url_produto = $session['url_produto'];
            $event->menu->add(strtoupper($session['name_produto']));
            $event->menu->add(
                [
                    'text'    => 'Dashboard',
                    'url'     => url('admin/' . $url_produto),
                    'icon'     => 'dashboard'
                ]);
            foreach($this->menuProduto($url_produto) as $menu){
                $event->menu->add($menu); 
            }
            $event->menu->add(
                [
                    'text'    => 'Material promocional',
                    'icon'    => 'archive',
                    'url'    => url('admin/'.$url_produto.'/arquivos'),
                ],
                [
                    'text'    => 'Ajuda',
                    'icon'    => 'life-ring',
                    'url'    => url('admin/'.$url_produto.'/ajuda'),
                ]);
           if(auth()->user()->level == 'S'){
            $event->menu->add(
                            [
                        'text'    => 'Administração',
                        'icon'    => 'cog',
                        'submenu' => [
                            [
                                'text'    => 'Ajuda',
                                'url'     => url('admin/administracao/ajuda'),
                                'icon'     => 'circle'
                            ],
                            [
                                'text'    => 'Parceiros',
                                'url'     => url('admin/administracao/parceiros'),
                                'icon'     => 'circle'
                            ]
                        ]
                    ]
                );
            }
        });

    }

    private function menuProduto($url_produto){
        switch ($url_produto) {
            case 'imoveis':
                return [[
                    'text'    => 'Indicação',
                    'icon'    => 'pencil-square',
                    'submenu' => [
                        [
                            'text'    => 'Comprar',
                            'url'     => url('admin/'.$url_produto.'/indicacao/comprar'),
                            'icon'     => 'circle'
                        ],
                        [
                            'text'    => 'Proprietário',
                            'url'     => url('#'),
                            'icon'     => 'circle',
                            'submenu' => [
                                [
                                    'text' => 'Vender',
                                    'url'  => '#',
                                    'icon'     => 'angle-double-right'
                                ],
                                [
                                    'text' => 'Alugar',
                                    'url'  => url('#'),
                                    'icon'     => 'angle-double-right'
                                ]
                            ],
                        ],
                        [
                            'text'    => 'Interessado',
                            'url'     => url('#'),
                            'icon'     => 'circle',
                            'submenu' => [
                                [
                                    'text' => 'Alugar',
                                    'url'  => url('#'),
                                    'icon'     => 'angle-double-right'
                                ]
                            ],
                        ]
                    ]
                    ],
                    [
                        'text'    => 'Relatórios',
                        'icon'    => 'pie-chart',
                        'url'     => url('admin/'.$url_produto.'/relatorios')
                    ]];
                break;
        }
    }
}