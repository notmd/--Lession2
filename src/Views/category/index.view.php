<div class="row mt-4">
    <div class="col">
        <?php if (isset($_SESSION['_flash']['status'])) : ?>
            <div class="alert alert-<?= $_SESSION['_flash']['status'] ?>" role="alert">
                <?= $_SESSION['_flash']['message'] ?>
            </div>
        <?php endif ?>
        <form action="">
            <label for="search" class="form-label">Search</label>
            <div class="d-flex">
                <input type="text" name="search" class="form-control" id="search" placeholder="Enter category name" value="<?= $search ?>">
                <button class="btn btn-primary ms-2" type="submit">Search</button>
            </div>
        </form>
        <div class="d-flex justify-content-between mt-2">
            <p class="fw-light">Found <?= $categories->total() ?> results.</p>
            <button class="btn btn-primary" type="submit" data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
        </div>
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create new category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input name="name" type="text" class="form-control" id="exampleInputEmail1">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <th scope="row" style="vertical-align: middle;"><?= $category->id ?></th>
                        <td style="vertical-align: middle;"><?= $category->name ?></td>
                        <td style="vertical-align: middle;" style="width: 1%; white-space: nowrap;">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#editModal_<?= $category->id ?>"></i>
                                <form method="post" id="deleteForm_<?= $category->id ?>">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="category_id" value="<?= $category->id ?>">
                                    <i class="fa-solid fa-circle-minus ms-2" data-bs-toggle="tooltip" title="Delete" onclick="if(confirm('Are you sure want to delete this category and its product?')) document.getElementById('deleteForm_<?= $category->id ?>').submit()"></i>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php foreach ($categories as $category) : ?>
            <!-- Edit modal -->
            <div class="modal fade" id="editModal_<?= $category->id ?>" tabindex="-1" aria-labelledby="editModal_<?= $category->id ?>_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModal_<?= $category->id ?>_label">Edit category <?= $category->id ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="category_id" value="<?= $category->id ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_name_<?= $category->id ?>" class="form-label">Name</label>
                                    <input name="name" value="<?= $category->name ?>" type="text" class="form-control" id="edit_name_<?= $category->id ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
