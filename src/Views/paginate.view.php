<nav aria-label="Page navigation example">
    <ul class="pagination">
        <li class="page-item <?= $paginator->previousPageUrl() ? '' : 'disabled' ?>"><a class="page-link" href="<?= $paginator->previousPageUrl() ? $paginator->previousPageUrl() : '#' ?>">Previous</a></li>
        <?php foreach (array_fill(1,  $paginator->lastPage(), 0) as $page => $value) : ?>
            <li class="page-item"><a class="page-link" href="<?= $paginator->url($page) ?>"><?= $page ?></a></li>
        <?php endforeach ?>
        <li class="page-item <?= $paginator->nextPageUrl() ? '' : 'disabled' ?>"><a class="page-link" href="<?= $paginator->nextPageUrl() ? $paginator->nextPageUrl() : '#' ?>">Next</a></li>
    </ul>
</nav>
