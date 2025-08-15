<div class="d-flex flex-column align-items-center w-75">
    <div class="d-flex justify-content-between w-100 mx-3 my-2 px-4 py-2 bg-primary">
        <span>Menu</span>
        <a class="bg-success text-white text-decoration-none px-2 py-1 rounded-2" href="menu/create">Nuevo</a>
    </div>
    <table class="table table-striped w-100">
        <thead>
            <tr>
                <td>ID</td>
                <td>Nombre</td>
                <td>Menu Padre</td>
                <td>Descripción</td>
                <td>Acciones</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $menus = $data['menus'];
            foreach ($menus as $menu):
                $id = $menu['id'];
                $name = $menu['name'];
                $parentName = isset($menu['parent']) ? $menu['parent']['name'] : '';
                $description = $menu['description'];
        ?>
            <tr>
                <td>
                    <?= $id ?>
                </td>
                <td>
                    <?= $name ?>
                </td>
                <td>
                    <?= $parentName ?>
                </td>
                <td>
                    <?= $description ?>
                </td>
                <td>
                    <a href="menu/edit/<?= $menu['id'] ?>" class='btn btn-info text-decoration-none'>Editar</a>
                    <a href="menu/delete/<?= $menu['id'] ?>" class='btn btn-danger text-decoration-none'>Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
