<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use App\Models\Oi\PlanosCategoria;
use App\Helpers\Helper;


class AdminController extends Controller
{
    function __construct(Request $request, Dispatcher $events)
    {
        $this->middleware('auth');
        
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $session = session()->get('portalparceiros');
            $url_produto = $session['produtos']['url_produto'];
            
            $event->menu->add(strtoupper($session['produtos']['name_produto']));
            $event->menu->add(
                [
                    'text'    => 'Dashboard',
                    'url'     => url('admin/' . $url_produto . '/dashboard'),
                    'icon'     => 'dashboard'
                ]);
            foreach($this->menuProduto($url_produto) as $menu){
                $event->menu->add($menu); 
            }
            $event->menu->add(
            [
                'text'    => 'Relatórios',
                'icon'    => 'pie-chart',
                'url'     => url('admin/'.$url_produto.'/relatorios')
            ]);
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
                            ],
                            [
                                'text'    => 'Planos',
                                'url'     => url('admin/administracao/planos'),
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
                            'text'    => 'Interessado',
                            'icon'     => 'circle',
                            'submenu' => [
                                [
                                    'text'    => 'Comprar',
                                    'url'     => url('admin/'.$url_produto.'/indicacao?type=I&trade=C'),
                                    'icon'     => 'angle-double-right'
                                ],
                                [
                                    'text' => 'Alugar',
                                    'url'  => url('admin/'.$url_produto.'/indicacao?type=I&trade=A'),
                                    'icon'     => 'angle-double-right'
                                ]
                            ]    
                        ],
                            [
                                'text'    => 'Proprietário',
                                'icon'     => 'circle',
                                'submenu' => [
                                    [
                                        'text' => 'Vender',
                                        'url'  => url('admin/'.$url_produto.'/indicacao?type=P&trade=V'),
                                        'icon'     => 'angle-double-right'
                                    ],
                                    [
                                        'text' => 'Alugar',
                                        'url'  => url('admin/'.$url_produto.'/indicacao?type=P&trade=A'),
                                        'icon'     => 'angle-double-right'
                                    ]
                                ],
                            ]
                        ]
                    ]];
                break;
            case 'oi':
                return [
                    [
                        'text'    => 'Sobre',
                        'icon'    => 'info-circle',
                        'url'     => url('admin/'.$url_produto.'/sobre'),
                    ],
                    [
                    'text'    => 'Indicação',
                    'icon'    => 'pencil-square',
                    'submenu' => [
                            [
                                'text'    => 'Fechar contrato',
                                'icon'    => 'angle-double-right',
                                'url'     => url('admin/'.$url_produto.'/indicacao/fechar-contrato'),
                            ],
                            [
                                'text'    => 'Solicitar atendimento',
                                'icon'    => 'angle-double-right',
                                'url'     => url('admin/'.$url_produto.'/indicacao/solicitar-atendimento'),
                            ]
                        ]
                    ],
                    [
                        'text'    => 'Planos',
                        'icon'    => 'paypal',
                        'submenu' => $this->planosList()
                    ],
                ];
                
                break;
            case 'financiamento':
                return [[
                    'text'    => 'Sobre',
                    'icon'    => 'info-circle',
                    'url'     => url(''),
                ],[
                    'text'    => 'Indicação',
                    'icon'    => 'pencil-square',
                    'submenu' => [
                        [
                            'text' => 'Tradicional',
                            'icon'    => 'angle-double-right',
                            'url'     => url('admin/'.$url_produto.'/indicacao/tradicional'),
                        ],
                        [
                            'text' => 'Refinanciamento',
                            'icon'    => 'angle-double-right',
                            'url'     => url('admin/'.$url_produto.'/indicacao/refinanciamento'),
                        ]
                    ]
                    ]];
                break;
            case 'consultoria-de-credito':
                return [[
                    'text'    => 'Sobre',
                    'icon'    => 'info-circle',
                    'url'     => url(''),
                ],
                [
                    'text'    => 'Imóveis',
                    'icon'    => 'home',
                    'submenu' => [
                        [
                            'text' => 'Indicação',
                            'icon'    => 'circle',
                            'url'     => url('#'),
                        ],
                        [
                            'text' => 'Parceiros',
                            'icon'    => 'circle',
                            'url'     => url('#'),
                        ]
                    ]
                ],
                [
                    'text'    => 'Veículos',
                    'icon'    => 'car',
                    'submenu' => [
                        [
                            'text' => 'Indicação',
                            'icon'    => 'circle',
                            'url'     => url('#'),
                        ],
                        [
                            'text' => 'Parceiros',
                            'icon'    => 'circle',
                            'url'     => url('#'),
                        ]
                    ]
                    ]
                ];
                break;

                default:
                return [];
                break;
        }
    }

    private function planosList(){
        $category = PlanosCategoria::orderBy('name')->get();
        $retorno = [];
        foreach($category as $key => $value){
            array_push($retorno, [
                'text'    => $value['name'],
                'icon'    => 'angle-double-right',
                'url'     => url('admin/oi/planos/'.$value['url']),
            ]);
        }
        return $retorno;
    }
}