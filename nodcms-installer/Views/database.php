<?php \Config\Services::layout()->addJsFile("assets/nodcms/js/installer-database.min"); ?>
<p>In this step, database will be created.</p>
<p><strong>NOTE:</strong> Currently NodCMS and NodCMS installation wizard support only <strong>MySQL database</strong>.</p>
<p class="alert alert-warning"><strong class="font-red">NOTE:</strong> This stage will re-create and overwrite all your existing tables. You may lose your data on these tables.</p>
<h2>General Tables</h2>
<div class="border-1 p-3 bg-grey-200">
    <div class="row">
        <?php foreach($tables[0] as $item){ ?>
            <div class="col-md-3">
                <div class="border-1 border-grey-cascade p-2 mb-2 bg-grey-cararra">
                    <form method="post" data-submit="ajax" action="<?php echo $this->self_url; ?>">
                        <div class="d-flex justify-content-between">
                            <div><?php echo $item['table']; ?></div>
                            <div><i class="fa font-grey-mint <?php echo $item['exists'] ? "fa-check" : "fa-times"; ?>"></i></div>
                        </div>
                        <?php if($item['exists']){ ?>
                            <div class="text-warning">Table already exists.</div>
                        <?php } ?>
                        <input name="path" type="hidden" value="<?php echo $item['path']; ?>">
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php foreach($tables[1] as $model=>$_tables){ ?>
    <h2 class="mt-3"><?php echo $model; ?> Module</h2>
    <div class="border-1 p-3 bg-grey-200">
        <div class="row">
            <?php foreach($_tables as $item){ ?>
                <div class="col-md-3">
                    <div class="border-1 border-grey-cascade p-2 mb-2 bg-grey-cararra">
                        <form method="post" data-submit="ajax" action="<?php echo $this->self_url; ?>">
                            <div class="d-flex justify-content-between">
                                <div><?php echo $item['table']; ?></div>
                                <div><i class="fa font-grey-mint <?php echo $item['exists'] ? "fa-check" : "fa-times"; ?>"></i></div>
                            </div>
                            <?php if($item['exists']){ ?>
                                <div class="text-warning">Table already exist.</div>
                            <?php } ?>
                            <input name="path" type="hidden" value="<?php echo $item['path']; ?>">
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<div class="mt-3">
    <a href="<?php echo $this->back_url; ?>" class="btn default">Back</a>
    <button data-role="submit-installer" class="btn btn-success mr-1" data-next="<?php echo $this->next_url; ?>">Create tables</button>
</div>
