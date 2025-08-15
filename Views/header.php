<?php
    $menusNavbar = $data['menusNavbar'];

    function printNav($menus, $isSubmenu = false) {
        $menusNav = "";
        foreach ($menus as $menu) {
                $submenuItems = isset($menu['submenus']) ? $menu['submenus'] : null;
                
                if ($submenuItems) {
                    $submenus = printNav($submenuItems, true);
                }

                if(!$isSubmenu) {

                    $menusNav .= "
                        <div class='dropdown'>
                            <button class='h-100 bg-light border-0 px-3 bg-transparnt dropdown-toggle' data-bs-auto-close='outside' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                {$menu['name']}
                            </button>
                            <ul class='dropdown-menu'>
                                $submenus
                            </ul>
                        </div>";
                    
                } else {
                    if ($submenuItems) {
                        $menusNav .= "
                            <li class='dropdown-submenu dropend'>
                                <button class='dropdown-item dropdown-toggle' data-bs-auto-close='outside' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    {$menu['name']}
                                </button>
                                <ul class='dropdown-menu'>
                                    $submenus
                                </ul>
                            </li>";
                    } else {
                        $menusNav .= "<li><a class='dropdown-item' href='menu/show/{$menu['id']}'>{$menu['name']}</a></li>";
                    }
                }
            }

        return $menusNav;
    }
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 py-0" style="height:60px">
        <div class="container-fluid justify-content-start gap-3 h-100">
            <span class="navbar-brand">Evaluación</span>
            <div class="d-flex gap-1 h-100">
                <?= printNav($menusNavbar)?>
            </div>
        </div>
    </nav>
</header>