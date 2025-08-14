<?php
    $menus = $data['menus'];

    $dataMenuUpdate = isset($data['menuUpdate'])? $data['menuUpdate'][0] : null;
    $nameValue = '';
    $descriptionValue = ''; 
    $parentSelected = '';
    $action = 'action="store"';
    $pathCancel = '../';

    if ($dataMenuUpdate) {
        $pathCancel .= '../';
    }

    if ($dataMenuUpdate) {
        $id = $dataMenuUpdate['id'];
        $nameValue = $dataMenuUpdate['name'];
        $descriptionValue = $dataMenuUpdate['description'];
        $parentSelected = $dataMenuUpdate['id_parent'];
        $action = "action='../update/$id'";
    }
?>

<div class="d-flex flex-column align-items-center w-75">
    <div class="w-75 mx-3 mt-2 px-4 py-2 bg-primary">
        <span class="text-white">Formulario</span>
    </div>

    <div class="d-flex justify-content-center w-75 border border-1">
        <form method="POST" <?= $action ?> class="d-flex flex-column gap-3 w-50">
            <div class="d-flex justify-content-between mx-5 mt-5">
                <label for="parent">Menú Padre</label>
                <select id="parent" name="parent" class="form-control" style="width: 200px;">
                    <option <?= $parentSelected ?? 'selected' ?> value="">Elige el Menú padre</option>
                    <?php 
                        foreach ($menus as $menu):
                            $id = $menu['id'];
                            $name = $menu['name'];
                            $isSelected = $id == $parentSelected;
                            $menuChoosen = null;

                            if ($dataMenuUpdate) {
                                $menuChoosen = $dataMenuUpdate['id'];
                            }
                            if ($menuChoosen != $id):
                    ?>
                        <option <?= $isSelected ? 'selected' : ''; ?> value="<?= $id ?>"><?= $name ?></option>
                    <?php
                            endif;
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="d-flex justify-content-between mx-5">
                <label for="name">Nombre</label>
                <input id="name" name="name" class="form-control" style="width: 200px;" value="<?= $nameValue?>"/>
            </div>
            <div class="d-flex justify-content-between mx-5">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" class="form-control" style="width: 200px;"><?= $descriptionValue?></textarea>
            </div>
            <div class="d-flex justify-content-between my-5">
                <a href="<?= $pathCancel ?>" class="btn btn-danger">Cancelar</a>
                <button class="btn btn-primary">Enviar</button>
            </div>
        </form>
    </div>
</div>