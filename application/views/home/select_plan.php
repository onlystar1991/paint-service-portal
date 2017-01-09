<script src="https://checkout.stripe.com/checkout.js"></script>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            <h3 class="choose-plan">Choose a Plan</h3>
        </div>
    </div>
    <div class="row">
        <div data-plan="basic" class="plan-picker col-xs-12 col-sm-6 col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        40GB
                    </h3>
                    (approx 5hours iphone 6 - 60 FPS)
                </div>

                <div class="panel-body">
                    <div class="the-price">
                        <h3> $4.95/month</h3>
                        <strong>Or</strong>
                        <h3> $49.99/year</h3>
                    </div>
                </div>
                <div class="panel-footer">
                </div>
            </div>
        </div>
        <div data-plan="medium" class="plan-picker col-xs-12 col-sm-6 col-md-4">
            <div class="panel panel-primary">
                <div class="cnrflash">
                    <div class="cnrflash-inner">
                        <span class="cnrflash-label">
                            MOST <br> POPULR
                        </span>
                    </div>
                </div>
                <div class="panel-heading">
                    <h3 class="panel-title">
                        100GB
                    </h3>
                    (approx 12hours iphone 6 - 60 FPS)
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h3> $9.95/month</h3>
                        <strong>Or</strong>
                        <h3> $99.99/year</h3>
                    </div>
                </div>
                <div class="panel-footer">
                </div>
            </div>
        </div>

        <div data-plan="super" class="plan-picker col-xs-12 col-sm-6 col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        250GB
                    </h3>
                    (approx 30hours iphone 6 - 60 FPS)
                </div>
                <div class="panel-body">
                    <div class="the-price">
                        <h3> $9.95/month</h3>
                        <strong>Or</strong>
                        <h3> $99.99/year</h3>
                    </div>
                </div>
                <div class="panel-footer">
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
    <a href="<?= base_url()?>/home/dashboard" class="btn btn-primary">Continue with free trial!</a>
</div>


<div id="select_monthly_or_yearly" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Do you want to pay monthly or yearly?</h4>
            </div>
            <div class="modal-footer">
                <button id="pay-monthly" class="btn btn-primary" style="border-radius: 1px;">Pay Monthly</button>
                <button id="pay-yearly" class="btn btn-default" style="border-radius: 1px;">Pay Yearly</button>
            </div>
        </div>
    </div>
</div>
