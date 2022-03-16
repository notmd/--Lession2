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
                <input type="text" name="search" class="form-control" id="search" placeholder="Product name or Category name" value="<?= $search ?>">
                <button class="btn btn-primary ms-2" type="submit">Search</button>
            </div>
        </form>
        <div class="d-flex justify-content-between mt-2">
            <p class="fw-light">Found <?= $products->total() ?> results.</p>
            <button class="btn btn-primary" type="submit" data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
        </div>
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create new Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input name="name" type="text" class="form-control" id="exampleInputEmail1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Category</label>
                                <select class="form-select" aria-label="Default select example" name="category_id">
                                    <option>Select category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= $category->id ?>"><?= $category->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Image</label>
                                <input class="form-control" type="file" id="formFile" name="image">
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
                    <th scope="col">Product name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Image</th>
                    <th scope="col">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <th scope="row" style="vertical-align: middle;"><?= $product->id ?></th>
                        <td style="vertical-align: middle;"><?= $product->name ?></td>
                        <td style="vertical-align: middle;"><?= $product->category_name ?></td>
                        <td style="vertical-align: middle;">
                            <img src=" <?= $product->image_url ?>" width="80px" alt="<?= $product->name ?>">
                        </td>
                        <td style="vertical-align: middle;">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#editModal_<?= $product->id ?>"></i>
                                <form method="post" id="deleteForm_<?= $product->id ?>">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                    <i class="fa-solid fa-circle-minus ms-2" data-bs-toggle="tooltip" title="Delete" onclick="if(confirm('Are you sure want to delete this product')) document.getElementById('deleteForm_<?= $product->id ?>').submit()"></i>
                                </form>
                                <form method="post" id="copyForm_<?= $product->id ?>" action="/products/copy">
                                    <input type="hidden" name="product_id" value="<?= $product->id ?>">
                                    <i class="fa-solid fa-copy ms-2" data-bs-toggle="tooltip" title="Copy" onclick="document.getElementById('copyForm_<?= $product->id ?>').submit()"></i>
                                </form>
                                <i class="fa-solid fa-eye ms-2" data-bs-toggle="modal" data-bs-target="#viewModal_<?= $product->id ?>"></i>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            <?php
            $products->render()
            ?>
        </div>
        <?php foreach ($products as $product) : ?>
            <!-- Edit modal -->
            <div class="modal fade" id="editModal_<?= $product->id ?>" tabindex="-1" aria-labelledby="editModal_<?= $product->id ?>_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModal_<?= $product->id ?>_label">Edit Product <?= $product->id ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="" enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="product_id" value="<?= $product->id ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit_name_<?= $product->id ?>" class="form-label">Name</label>
                                    <input name="name" value="<?= $product->name ?>" type="text" class="form-control" id="edit_name_<?= $product->id ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_category_<?= $product->id ?>" class="form-label">Category</label>
                                    <select class="form-select" aria-label="Default select example" id="edit_category_<?= $product->id ?>" name=" category_id">
                                        <option>Select category</option>
                                        <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category->id ?>" <?= $category->id === $product->category_id ? 'selected' : '' ?>><?= $category->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Image</label>
                                    <input class="form-control" type="file" id="formFile" name="image">
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
            <!-- View modal -->
            <div class="modal fade" id="viewModal_<?= $product->id ?>" tabindex="-1" aria-labelledby="editModal_<?= $product->id ?>_label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewModal_<?= $product->id ?>_label">Product <?= $product->id ?> detail</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bolder">Name</label>
                                <div>
                                    <?= $product->name ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bolder">Category</label>
                                <div>
                                    <?= $product->category_name ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bolder">Image</label>
                                <img src="<?= $product->image_url ?>" height="100px" width="100px" class="d-block">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
