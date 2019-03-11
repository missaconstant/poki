<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <h5 class="card-title">
                    Alerts
                </h5>
                <p class="text-muted">
                    Just to show alert boxes !
                </p>

                <?= ViewProvider::alert([
                    "type"    => "success",
                    "message" => "This is a small message and i will get it for u !"
                ]) ?>

                <?= ViewProvider::alert([
                    "type"    => "danger",
                    "message" => "This is a small message and i will get it for u !",
                    "dismissible" => true
                ]) ?>

                <?= ViewProvider::alert([
                    "type"    => "info",
                    "message" => "This is a small message and i will get it for u !"
                ]) ?>

                <?= ViewProvider::alert([
                    "type"    => "warning",
                    "message" => "This is a small message and i will get it for u !"
                ]) ?>

            </div>
        </div>
    </div>
</div>