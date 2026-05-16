<div class="container">
    <!-- مودال پرداخت فاکتور -->
    <div id="paymentModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">

                <div class="modal-header" style="background: #0e2d55; border-bottom: none; padding: 20px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 0.8;">&times;</button>
                    <h3 class="modal-title" id="paymentModalLabel" style="color: white; font-weight: 600;">
                        <i class="fa fa-credit-card"></i> پرداخت فاکتور
                    </h3>
                </div>

                <div class="modal-body" id="paymentModalBody" style="padding: 25px; background: #f8fafc;">
                    <!-- اطلاعات فاکتور با JS میاد -->
                </div>

                <div class="d-flex justify-content-center gap-3" style="padding: 20px 25px 25px; background: #f8fafc; gap: 12px;">
                    <button type="button" class="btn btn-beta-outline" data-dismiss="modal" style="padding: 8px 25px;">انصراف</button>
                    <button type="button" class="btn btn-beta-solid" id="confirmPaymentBtn" style="background: #0e2d55; padding: 8px 25px;">پرداخت</button>
                </div>

            </div>
        </div>
    </div>
</div>