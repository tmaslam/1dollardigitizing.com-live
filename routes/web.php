<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminPasswordController;
use App\Http\Controllers\AdminTwoFactorController;
use App\Http\Controllers\AdminBillingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEmailTemplateController;
use App\Http\Controllers\AdminOrderEntryController;
use App\Http\Controllers\AdminOrderDetailController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AdminPeopleController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminQuickQuoteController;
use App\Http\Controllers\AdminSimulationController;
use App\Http\Controllers\AdminSiteOfferController;
use App\Http\Controllers\AdminSiteContactController;
use App\Http\Controllers\AdminSitePaymentController;
use App\Http\Controllers\AdminSitePricingController;
use App\Http\Controllers\AdminToolsController;
use App\Http\Controllers\AuthLandingController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerTwoFactorController;
use App\Http\Controllers\CustomerOrderEntryController;
use App\Http\Controllers\CustomerPasswordController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\CustomerRegistrationController;
use App\Http\Controllers\CustomerSiteController;
use App\Http\Controllers\FreelancePaymentRequestController;
use App\Http\Controllers\TeamAuthController;
use App\Http\Controllers\TeamDashboardController;
use App\Http\Controllers\TeamDesignInfoController;
use App\Http\Controllers\TeamJobHistoryController;
use App\Http\Controllers\TeamOrderDetailController;
use App\Http\Controllers\TeamOrdersController;
use App\Http\Controllers\TeamQuickQuoteController;
use App\Http\Controllers\TeamSupervisorController;
use App\Http\Controllers\FreelanceQuoteController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\AdminBlogController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LegacyMigrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileConverterController;

$adminPrefix = trim((string) config('sites.admin_prefix', 'admin'), '/');
$internalPortalPath = trim((string) config('sites.internal_portal_path', 'portal'), '/');

Route::middleware('detect.site')->group(function () use ($adminPrefix, $internalPortalPath) {
    Route::get('/robots.txt', [CustomerSiteController::class, 'robots']);
    Route::get('/sitemap.xml', [CustomerSiteController::class, 'sitemap']);
    Route::get('/', [CustomerSiteController::class, 'home']);
    Route::get('/index.php', [CustomerSiteController::class, 'home']);
    Route::get('/home.php', [CustomerSiteController::class, 'home']);
    Route::get('/index-new.php', [CustomerSiteController::class, 'home']);
    Route::get('/about-us.php', [CustomerSiteController::class, 'about']);
    Route::get('/our-quality.php', [CustomerSiteController::class, 'quality']);
    Route::get('/work-process.php', [CustomerSiteController::class, 'workProcess']);
    Route::get('/our-services.php', [CustomerSiteController::class, 'services']);
    Route::get('/embroidery-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'embroidery-digitizing');
    Route::get('/3d-puff-embroidery-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', '3d-puff-embroidery-digitizing');
    Route::get('/applique-embroidery-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'applique-embroidery-digitizing');
    Route::get('/chain-stitch-embroidery-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'chain-stitch-embroidery-digitizing');
    Route::get('/photo-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'photo-digitizing');
    Route::get('/vector-art.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'vector-art');
    Route::get('/hat-cap-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'hat-cap-digitizing');
    Route::get('/left-chest-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'left-chest-digitizing');
    Route::get('/patch-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'patch-digitizing');
    Route::get('/jacket-back-digitizing.php', [CustomerSiteController::class, 'servicePage'])->defaults('section', 'jacket-back-digitizing');
    Route::get('/formats.php', [CustomerSiteController::class, 'formats']);
    Route::redirect('/file-formats.php', '/formats.php');
    Route::redirect('/machine-embroidery-formats.php', '/formats.php#embroidery-formats');
    Route::redirect('/vector-file-formats.php', '/formats.php#vector-formats');
    Route::get('/price-plan.php', [CustomerSiteController::class, 'pricing']);
    Route::get('/payment-options.php', [CustomerSiteController::class, 'paymentOptions']);
    Route::get('/privacy-policy.php', [CustomerSiteController::class, 'privacyPolicy']);
    Route::get('/terms.php', [CustomerSiteController::class, 'terms']);
    Route::match(['get', 'post'], '/instant-payment.php', [CustomerPaymentController::class, 'instantPaymentLanding']);
    Route::match(['get', 'post'], '/instant-payment-paypal.php', [CustomerPaymentController::class, 'instantPaymentLanding']);
    Route::get('/contact-us.php', [CustomerSiteController::class, 'contact']);
    Route::post('/contact-us.php', [CustomerSiteController::class, 'sendContact']);
    Route::get('/book-a-meeting.php', [CustomerSiteController::class, 'bookAMeeting']);
    Route::get('/blog', [BlogController::class, 'index']);
    Route::get('/blog/{slug}', [BlogController::class, 'show']);
    Route::get('/migration.php', [LegacyMigrationController::class, 'handle']);
    Route::get('/simulate-2checkout/{transaction}/checkout', [CustomerPaymentController::class, 'showTwocheckoutSimulator'])->middleware('customer.auth');
    Route::match(['get', 'post'], '/simulate-2checkout/{transaction}', [CustomerPaymentController::class, 'simulateTwocheckout'])->middleware('customer.auth');
    Route::get('/dashboard.php', [CustomerPortalController::class, 'dashboard'])->middleware(['legacy.upgrade', 'customer.auth']);
    Route::post('/select-plan.php', [CustomerPortalController::class, 'selectPlan'])->middleware('customer.auth');
    Route::post('/subscription/pause-request', [CustomerPortalController::class, 'subscriptionPauseRequest'])->middleware('customer.auth');
    Route::post('/subscription/cancel-request', [CustomerPortalController::class, 'subscriptionCancelRequest'])->middleware('customer.auth');
    Route::post('/subscription/reactivate-request', [CustomerPortalController::class, 'subscriptionReactivateRequest'])->middleware('customer.auth');
    Route::post('/subscription/change-request', [CustomerPortalController::class, 'subscriptionChangeRequest'])->middleware('customer.auth');
    Route::get('/stripe-return.php', [CustomerPortalController::class, 'stripeReturn'])->middleware('customer.auth');
    Route::get('/payment-success.php', [CustomerPortalController::class, 'paymentSuccess'])->middleware('customer.auth');
    Route::get('/new-order.php', [CustomerOrderEntryController::class, 'create'])->middleware('customer.auth');
    Route::post('/new-order.php', [CustomerOrderEntryController::class, 'store'])->middleware('customer.auth');
    Route::get('/vector-order.php', [CustomerOrderEntryController::class, 'create'])->middleware('customer.auth');
    Route::post('/vector-order.php', [CustomerOrderEntryController::class, 'store'])->middleware('customer.auth');
    Route::get('/quote.php', [CustomerOrderEntryController::class, 'create'])->middleware('customer.auth');
    Route::post('/quote.php', [CustomerOrderEntryController::class, 'store'])->middleware('customer.auth');
    Route::get('/digitizing_quote.php', [CustomerOrderEntryController::class, 'create'])->middleware('customer.auth');

    // Public tools (no auth required)
    Route::get('/tools/file-converter', [FileConverterController::class, 'showForm'])->name('tools.file-converter');
    Route::post('/tools/file-converter', [FileConverterController::class, 'convert'])->name('tools.file-converter.post');
    Route::post('/digitizing_quote.php', [CustomerOrderEntryController::class, 'store'])->middleware('customer.auth');
    Route::get('/digitizing-quote.php', [CustomerOrderEntryController::class, 'create'])->middleware('customer.auth');
    Route::post('/digitizing-quote.php', [CustomerOrderEntryController::class, 'store'])->middleware('customer.auth');
    Route::get('/vector_quote.php', [CustomerOrderEntryController::class, 'create'])->middleware('customer.auth');
    Route::post('/vector_quote.php', [CustomerOrderEntryController::class, 'store'])->middleware('customer.auth');
    Route::get('/vector-quote.php', [CustomerOrderEntryController::class, 'create'])->middleware('customer.auth');
    Route::post('/vector-quote.php', [CustomerOrderEntryController::class, 'store'])->middleware('customer.auth');
    Route::get('/edit-order.php', [CustomerOrderEntryController::class, 'edit'])->middleware('customer.auth');
    Route::post('/edit-order.php', [CustomerOrderEntryController::class, 'update'])->middleware('customer.auth');
    Route::get('/edit-quote.php', [CustomerOrderEntryController::class, 'edit'])->middleware('customer.auth');
    Route::post('/edit-quote.php', [CustomerOrderEntryController::class, 'update'])->middleware('customer.auth');
    Route::get('/disapprove-order.php', [CustomerOrderEntryController::class, 'revision'])->middleware('customer.auth');
    Route::post('/disapprove-order.php', [CustomerOrderEntryController::class, 'submitRevision'])->middleware('customer.auth');
    Route::get('/view-orders.php', [CustomerPortalController::class, 'orders'])->middleware('customer.auth');
    Route::get('/view-order-detail.php', [CustomerPortalController::class, 'showOrder'])->middleware('customer.auth');
    Route::get('/view-orderpaid-details.php', [CustomerPortalController::class, 'showOrder'])->middleware('customer.auth');
    Route::get('/view-quotes.php', [CustomerPortalController::class, 'quotes'])->middleware('customer.auth');
    Route::get('/view-quote-detail.php', [CustomerPortalController::class, 'showQuote'])->middleware('customer.auth');
    Route::get('/view-billing.php', [CustomerPortalController::class, 'billing'])->middleware('customer.auth');
    Route::get('/credit-activity.php', [CustomerPortalController::class, 'creditActivity'])->middleware('customer.auth');
    Route::get('/view-archive-orders.php', [CustomerPortalController::class, 'archive'])->middleware('customer.auth');
    Route::get('/download-paid-orders.php', [CustomerPortalController::class, 'downloadPaidOrdersZip'])->middleware('customer.auth');
    Route::get('/view-paid-orders.php', [CustomerAccountController::class, 'paidAdvanceOrders'])->middleware('customer.auth');
    Route::get('/view-invoices.php', [CustomerAccountController::class, 'invoices'])->middleware('customer.auth');
    Route::get('/view-invoice-detail.php', [CustomerAccountController::class, 'invoiceDetail'])->middleware('customer.auth');
    Route::get('/referral-invoice.php', [CustomerAccountController::class, 'referralInvoices'])->middleware('customer.auth');
    Route::get('/refund-apply.php', [CustomerAccountController::class, 'refundForm'])->middleware('customer.auth');
    Route::post('/refund-apply.php', [CustomerAccountController::class, 'submitRefund'])->middleware('customer.auth');
    Route::get('/my-profile.php', [CustomerAccountController::class, 'profile'])->middleware('customer.auth');
    Route::post('/my-profile.php', [CustomerAccountController::class, 'updateProfile'])->middleware('customer.auth');
    Route::post('/my-profile/password', [CustomerAccountController::class, 'updatePassword'])->middleware('customer.auth');
    Route::post('/my-profile/2fa', [CustomerAccountController::class, 'toggleTwoFactor'])->middleware('customer.auth');
    Route::get('/login-verify.php', [CustomerTwoFactorController::class, 'show'])->name('customer.2fa.show');
    Route::post('/login-verify.php', [CustomerTwoFactorController::class, 'verify'])->name('customer.2fa.verify');
    Route::post('/login-verify-resend.php', [CustomerTwoFactorController::class, 'resend'])->name('customer.2fa.resend');
    Route::get('/download.php', [CustomerPortalController::class, 'download'])->middleware('customer.auth');
    Route::get('/preview.php', [CustomerPortalController::class, 'preview'])->middleware('customer.auth');
    Route::post('/orders/{order}/approve', [CustomerPortalController::class, 'approve'])->middleware('customer.auth');
    Route::post('/orders/{order}/cancel', [CustomerPortalController::class, 'cancelOrder'])->middleware('customer.auth');
    Route::post('/quotes/{order}/switch-to-order', [CustomerPortalController::class, 'switchQuoteToOrder'])->middleware('customer.auth');
    Route::get('/approved-order.php', [CustomerPortalController::class, 'approveCompatibility'])->middleware('customer.auth');
    Route::post('/quotes/{order}/feedback', [CustomerPortalController::class, 'quoteFeedback'])->middleware('customer.auth');
    Route::post('/quotes/{order}/delete', [CustomerPortalController::class, 'deleteQuote'])->middleware('customer.auth');
    Route::post('/view-billing.php/pay-all', [CustomerPaymentController::class, 'startOutstanding'])->middleware('customer.auth');
    Route::post('/view-billing.php/pay-all-credit', [CustomerPaymentController::class, 'payAllWithCredit'])->middleware('customer.auth');
    Route::post('/view-billing.php/{billing}/pay', [CustomerPaymentController::class, 'startSingle'])->middleware('customer.auth');
    Route::post('/view-billing.php/{billing}/pay-credit', [CustomerPaymentController::class, 'paySingleWithCredit'])->middleware('customer.auth');
    Route::match(['get', 'post'], '/payment.php', [CustomerPaymentController::class, 'legacyDirectPayment'])->middleware('customer.auth');
    Route::match(['get', 'post'], '/payment-proceed.php', [CustomerPaymentController::class, 'legacyProceed']);
    Route::match(['get', 'post'], '/successpay.php', [CustomerPaymentController::class, 'handleReturn']);
    Route::match(['get', 'post'], '/successpay1.php', [CustomerPaymentController::class, 'handleReturn']);
    Route::match(['get', 'post'], '/successpay-paypal.php', [CustomerPaymentController::class, 'handleReturn']);
    Route::post('/payment-notification.php', [CustomerPaymentController::class, 'handleNotification']);
    Route::post('/webhooks/stripe', [CustomerPaymentController::class, 'handleStripeWebhook']);

    Route::get('/'.$internalPortalPath, [AuthLandingController::class, 'show']);
    Route::get('/'.$internalPortalPath.'/index.php', [AuthLandingController::class, 'show']);

    Route::get('/sign-up.php', [CustomerRegistrationController::class, 'show']);
    Route::post('/sign-up.php', [CustomerRegistrationController::class, 'register']);
    Route::get('/confirmation_registration.php', [CustomerRegistrationController::class, 'activate']);
    Route::get('/resend-verification.php', [CustomerRegistrationController::class, 'showResend']);
    Route::post('/resend-verification.php', [CustomerRegistrationController::class, 'resend']);
    Route::get('/plan-checkout.php', [CustomerRegistrationController::class, 'showPlanCheckout']);
    Route::get('/plan-checkout.php/pay', [CustomerRegistrationController::class, 'startPlanCheckoutPayment']);
    Route::get('/login', [CustomerAuthController::class, 'showLogin']);
    Route::get('/login.php', [CustomerAuthController::class, 'showLogin']);
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::post('/login.php', [CustomerAuthController::class, 'login']);
    Route::get('/forget-password.php', [CustomerPasswordController::class, 'showForgot']);
    Route::post('/forget-password.php', [CustomerPasswordController::class, 'sendResetLink']);
    Route::get('/reset-password.php', [CustomerPasswordController::class, 'showReset']);
    Route::post('/reset-password.php', [CustomerPasswordController::class, 'reset']);
    Route::get('/logout.php', [CustomerAuthController::class, 'logout']);

    Route::get('/'.$adminPrefix, [AdminAuthController::class, 'showLogin']);
    Route::get('/'.$adminPrefix.'/login', [AdminAuthController::class, 'showLogin']);
    Route::post('/'.$adminPrefix.'/login', [AdminAuthController::class, 'login']);
    Route::get('/'.$adminPrefix.'/logout', [AdminAuthController::class, 'logout']);
    Route::post('/stop-simulated-session', [AdminSimulationController::class, 'stop']);
});

Route::get('/v', [AdminAuthController::class, 'showLogin']);
Route::get('/v/index.php', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/v/login', [AdminAuthController::class, 'login']);
Route::get('/v/logout.php', [AdminAuthController::class, 'logout']);
Route::get('/v/login-2fa', [AdminTwoFactorController::class, 'show'])->name('admin.2fa.show');
Route::post('/v/login-2fa', [AdminTwoFactorController::class, 'verify'])->name('admin.2fa.verify');
Route::post('/v/login-2fa-resend', [AdminTwoFactorController::class, 'resend'])->name('admin.2fa.resend');
Route::get('/v/forgot-password', [AdminPasswordController::class, 'showForgot']);
Route::post('/v/forgot-password', [AdminPasswordController::class, 'sendResetLink']);
Route::get('/v/reset-password', [AdminPasswordController::class, 'showReset']);
Route::post('/v/reset-password', [AdminPasswordController::class, 'doReset']);

Route::middleware('admin.auth')->group(function () use ($adminPrefix) {
    Route::get('/welcome.php', [AdminDashboardController::class, 'index']);
    Route::get('/'.$adminPrefix.'/welcome', [AdminDashboardController::class, 'index']);
    Route::get('/v/welcome.php', [AdminDashboardController::class, 'index']);
    Route::match(['get', 'post'], '/v/orders.php', [AdminOrdersController::class, 'index']);
    Route::match(['get', 'post'], '/v/orders/{queue}', [AdminOrdersController::class, 'index']);
    Route::get('/v/create-order.php', [AdminOrderEntryController::class, 'create']);
    Route::post('/v/create-order.php', [AdminOrderEntryController::class, 'store']);
    Route::post('/v/create-order/price-preview', [AdminOrderEntryController::class, 'previewPrice']);
    Route::get('/v/show-all-orders.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'All Orders');
    Route::get('/v/new-orders.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'New Orders');
    Route::get('/v/disapproved-orders.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'disapproved-orders');
    Route::get('/v/assigned-orders.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'designer-orders');
    Route::get('/v/designer-completed-orders.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'designer-completed');
    Route::get('/v/approval-waiting-order.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'approval-waiting');
    Route::get('/v/new-quotes.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'New Quotes');
    Route::get('/v/assigned-quotes.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'Assigned Quotes');
    Route::get('/v/desinger-completed-quotes.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'Designer Completed Quotes');
    Route::get('/v/completed-quotes.php', [AdminOrdersController::class, 'compatibilityListRedirect'])->defaults('page', 'Completed Quotes');
    Route::get('/v/view-order-detail.php', [AdminOrderDetailController::class, 'show']);
    Route::get('/v/view-order-detail-2.php', [AdminOrdersController::class, 'compatibilityDetailRedirect']);
    Route::get('/v/orders/{order}/detail/{page?}', [AdminOrderDetailController::class, 'showByRoute']);
    Route::post('/v/orders/{order}/delete', [AdminOrdersController::class, 'destroy']);
    Route::post('/v/orders/{order}/mark-paid', [AdminOrdersController::class, 'markPaid']);
    Route::post('/v/orders/{order}/approve', [AdminOrdersController::class, 'approve']);
    Route::post('/v/order-detail/comments', [AdminOrderDetailController::class, 'addComment']);
    Route::post('/v/comments/{comment}/delete', [AdminOrderDetailController::class, 'deleteComment']);
    Route::post('/v/order-detail/upload', [AdminOrderDetailController::class, 'uploadAttachment']);
    Route::get('/v/attachments/{attachment}/download', [AdminOrderDetailController::class, 'downloadAttachment']);
    Route::get('/v/attachments/{attachment}/preview', [AdminOrderDetailController::class, 'previewAttachment']);
    Route::get('/v/attachments/{attachment}/preview/raw', [AdminOrderDetailController::class, 'previewAttachment'])->defaults('raw', 1);
    Route::post('/v/attachments/{attachment}/delete', [AdminOrderDetailController::class, 'deleteAttachment']);
    Route::post('/v/order-detail/select-for-customer', [AdminOrderDetailController::class, 'selectFilesForCustomer']);
    Route::post('/v/order-detail/delivery-controls', [AdminOrderDetailController::class, 'saveDeliveryControls']);
    Route::post('/v/orders/{order}/send-quote-followup', [AdminOrderDetailController::class, 'sendQuoteFollowUp']);
    Route::post('/v/order-detail/respond-quote-negotiation', [AdminOrderDetailController::class, 'respondToQuoteNegotiation']);
    Route::post('/v/order-detail/convert-quote', [AdminOrderDetailController::class, 'convertQuoteToOrder']);
    Route::post('/v/order-detail/price-preview', [AdminOrderDetailController::class, 'previewPrice']);
    Route::post('/v/order-detail/complete', [AdminOrderDetailController::class, 'complete']);
    Route::get('/v/view-quick-order-detail.php', [AdminQuickQuoteController::class, 'show']);
    Route::post('/v/quick-order/comments', [AdminQuickQuoteController::class, 'addComment']);
    Route::post('/v/quick-comments/{comment}/delete', [AdminQuickQuoteController::class, 'deleteComment']);
    Route::get('/v/quick-attachments/{attachment}/download', [AdminQuickQuoteController::class, 'downloadAttachment']);
    Route::get('/v/quick-attachments/{attachment}/preview', [AdminQuickQuoteController::class, 'previewAttachment']);
    Route::get('/v/quick-attachments/{attachment}/preview/raw', [AdminQuickQuoteController::class, 'previewAttachment'])->defaults('raw', 1);
    Route::post('/v/quick-attachments/{attachment}/delete', [AdminQuickQuoteController::class, 'deleteAttachment']);
    Route::post('/v/quick-order/complete', [AdminQuickQuoteController::class, 'complete']);
    Route::match(['get', 'post'], '/v/all-payment-due.php', [AdminBillingController::class, 'due']);
    Route::match(['get', 'post'], '/v/payment-recieved.php', [AdminBillingController::class, 'received']);
    Route::post('/v/billing/{billing}/delete', [AdminBillingController::class, 'destroy']);
    Route::match(['get', 'post'], '/v/customer_list.php', [AdminPeopleController::class, 'customers']);
    Route::get('/v/create-customer.php', [AdminPeopleController::class, 'createCustomer']);
    Route::post('/v/create-customer.php', [AdminPeopleController::class, 'storeCustomer']);
    Route::match(['get', 'post'], '/v/customer-approvals.php', [AdminPeopleController::class, 'pendingApprovals']);
    Route::post('/v/customers/{customer}/verify-email', [AdminPeopleController::class, 'verifyCustomerEmail']);
    Route::post('/v/customers/{customer}/approve', [AdminPeopleController::class, 'approveCustomer']);
    Route::post('/v/customers/{customer}/block', [AdminPeopleController::class, 'blockCustomer']);
    Route::post('/v/customers/{customer}/delete', [AdminPeopleController::class, 'deleteCustomer']);
    Route::match(['get', 'post'], '/v/show-all-teams.php', [AdminPeopleController::class, 'teams']);
    Route::post('/v/teams/{team}/disable', [AdminPeopleController::class, 'disableTeam']);
    Route::post('/v/teams/{team}/unlock', [AdminPeopleController::class, 'unlockTeam']);
Route::post('/v/teams/{team}/destroy', [AdminPeopleController::class, 'destroyTeam']);
    Route::match(['get', 'post'], '/v/customer_list_sikandar.php', [AdminPeopleController::class, 'customers']);
    Route::get('/v/customer-detail.php', [AdminProfileController::class, 'customerShow']);
    Route::post('/v/customers/{customer}/reset-password', [AdminProfileController::class, 'resetCustomerPassword']);
    Route::post('/v/customers/{customer}/add-credit', [AdminProfileController::class, 'addCustomerCredit']);
    Route::get('/v/edit-customer-detail.php', [AdminProfileController::class, 'customerEdit']);
    Route::post('/v/edit-customer-detail.php', [AdminProfileController::class, 'customerUpdate']);
    Route::get('/v/change-password.php', [AdminProfileController::class, 'adminPasswordForm']);
    Route::post('/v/change-password.php', [AdminProfileController::class, 'adminPasswordSave']);
    Route::get('/v/change-password-verify.php', [AdminProfileController::class, 'adminPasswordVerifyForm']);
    Route::post('/v/change-password-verify.php', [AdminProfileController::class, 'adminPasswordVerify']);
    Route::get('/v/create-teams.php', [AdminProfileController::class, 'teamForm']);
    Route::post('/v/create-teams.php', [AdminProfileController::class, 'teamSave']);
    Route::get('/v/assign-order.php', [AdminProfileController::class, 'assignForm']);
    Route::post('/v/assign-order.php', [AdminProfileController::class, 'assignSave']);
    Route::post('/v/orders/{order}/accept-quote', [AdminProfileController::class, 'acceptFreelanceQuote']);
    Route::get('/v/freelance-payments.php', [FreelancePaymentRequestController::class, 'adminIndex']);
    Route::post('/v/freelance-payments/{paymentRequest}/pay', [FreelancePaymentRequestController::class, 'pay']);
    Route::match(['get', 'post'], '/v/payment-due-report.php', [AdminToolsController::class, 'dueReport']);
    Route::get('/v/payment-due-detail.php', [AdminToolsController::class, 'dueReportDetail']);
    Route::get('/v/pop-payment-due.php', [AdminToolsController::class, 'dueReportPopupRedirect']);
    Route::post('/v/payment-due-report/customer-pay', [AdminToolsController::class, 'dueReportPayCustomer']);
    Route::post('/v/payment-due-report/invoice/{billing}/pay', [AdminToolsController::class, 'dueReportPayInvoice']);
    Route::post('/v/payment-due-report/invoice/{billing}/apply-balance', [AdminToolsController::class, 'applyCustomerBalance']);
    Route::post('/v/payment-due-report/customer/apply-balance', [AdminToolsController::class, 'applyCustomerBalanceToCustomer']);
    Route::match(['get', 'post'], '/v/payment-recieved-report.php', [AdminToolsController::class, 'receivedReport']);
    Route::get('/v/payment-transactions.php', [AdminToolsController::class, 'paymentTransactions']);
    Route::get('/v/subscription-report.php', [AdminToolsController::class, 'subscriptionReport']);
    Route::get('/v/settled-credits-report.php', [AdminToolsController::class, 'settledCreditsReport']);
    Route::get('/v/payment-recieved-detail.php', [AdminToolsController::class, 'receivedReportDetail']);
    Route::get('/v/pop-payment-recieved.php', [AdminToolsController::class, 'receivedReportPopupRedirect']);
    Route::match(['get', 'post'], '/v/monthly-reports.php', [AdminToolsController::class, 'teamReport']);
    Route::match(['get', 'post'], '/v/login_history.php', [AdminToolsController::class, 'loginHistory']);
    Route::match(['get', 'post'], '/v/security-events.php', [AdminToolsController::class, 'securityEvents']);
    Route::post('/v/security-events/block-ip', [AdminToolsController::class, 'securityBlockIp']);
    Route::match(['get', 'post'], '/v/block-customer_list.php', [AdminToolsController::class, 'blockedCustomers']);
    Route::post('/v/block-customer_list/{customer}/unblock', [AdminToolsController::class, 'unblockCustomer']);
    Route::post('/v/block-customer_list/{customer}/delete', [AdminToolsController::class, 'deleteBlockedCustomer']);
    Route::get('/v/blocked-ip-list.php', [AdminToolsController::class, 'blockedIps']);
    Route::post('/v/blocked-ip-list.php', [AdminToolsController::class, 'storeBlockedIp']);
    Route::match(['get', 'post'], '/v/block_ip.php', [AdminToolsController::class, 'blockedIps']);
    Route::post('/v/blocked-ip-list/{blockIp}/delete', [AdminToolsController::class, 'deleteBlockedIp']);
    Route::get('/v/transaction-history.php', [AdminToolsController::class, 'transactionHistory']);
    Route::get('/v/pay-now.php', [AdminToolsController::class, 'paymentForm']);
    Route::post('/v/pay-now.php', [AdminToolsController::class, 'paymentSave']);
    Route::get('/v/customer-lookup.php', [AdminToolsController::class, 'customerLookup']);
    Route::get('/v/customer-payment-inventory.php', [AdminToolsController::class, 'customerPaymentInventory']);
    Route::get('/v/notify-customers.php', [AdminToolsController::class, 'notifyCustomers']);
    Route::post('/v/notify-customers.php', [AdminToolsController::class, 'sendNotifyCustomers']);
    Route::get('/v/email-templates.php', [AdminEmailTemplateController::class, 'index']);
    Route::get('/v/email-templates-create.php', [AdminEmailTemplateController::class, 'create']);
    Route::post('/v/email-templates-create.php', [AdminEmailTemplateController::class, 'store']);
    Route::get('/v/email-templates/{template}/edit', [AdminEmailTemplateController::class, 'edit']);
    Route::post('/v/email-templates/{template}/edit', [AdminEmailTemplateController::class, 'update']);
    Route::post('/v/email-templates/{template}/delete', [AdminEmailTemplateController::class, 'destroy']);
    Route::get('/v/site-contact.php', [AdminSiteContactController::class, 'index']);
    Route::post('/v/site-contact/{site}/edit', [AdminSiteContactController::class, 'update']);
    Route::get('/v/site-payments.php', [AdminSitePaymentController::class, 'index']);
    Route::post('/v/site-payments/{site}/edit', [AdminSitePaymentController::class, 'update']);
    Route::get('/v/site-pricing.php', [AdminSitePricingController::class, 'index']);
    Route::get('/v/site-pricing-create.php', [AdminSitePricingController::class, 'create']);
    Route::post('/v/site-pricing-create.php', [AdminSitePricingController::class, 'store']);
    Route::get('/v/site-pricing/{profile}/edit', [AdminSitePricingController::class, 'edit']);
    Route::post('/v/site-pricing/{profile}/edit', [AdminSitePricingController::class, 'update']);
    Route::post('/v/site-pricing/{profile}/delete', [AdminSitePricingController::class, 'destroy']);
    Route::post('/v/simulate-login/{user}', [AdminSimulationController::class, 'start']);
    Route::match(['get', 'post'], '/v/ordersquick.php', [AdminToolsController::class, 'quickQuotes']);
    Route::post('/v/ordersquick-delete', [AdminToolsController::class, 'deleteQuickQuotes']);
    Route::match(['get', 'post'], '/v/show-all-blogs.php', [AdminToolsController::class, 'blogs']);
    Route::post('/v/show-all-blogs/{blog}/delete', [AdminToolsController::class, 'deleteBlog']);

    // Blog management
    Route::get('/v/blogs', [AdminBlogController::class, 'index']);
    Route::get('/v/blogs/create', [AdminBlogController::class, 'create']);
    Route::post('/v/blogs', [AdminBlogController::class, 'store']);
    Route::get('/v/blogs/{blog}/edit', [AdminBlogController::class, 'edit']);
    Route::post('/v/blogs/{blog}/edit', [AdminBlogController::class, 'update']);
    Route::post('/v/blogs/{blog}/delete', [AdminBlogController::class, 'destroy']);
    Route::post('/v/blogs/{blog}/toggle-publish', [AdminBlogController::class, 'togglePublish']);
    Route::post('/v/blog-image-upload', [AdminBlogController::class, 'uploadImage']);

    // Temporary: one-time blog article insertion — remove after use
    Route::get('/v/run-blog-insert', function () {
        $inserted = [];
        $today    = now()->format('Y-m-d');

        $articles = [
            [
                'title'            => 'How to Calculate Stitch Counts for Custom Hats and Jackets',
                'slug'             => 'how-to-calculate-stitch-counts-for-custom-hats-and-jackets',
                'excerpt'          => 'Estimating stitch counts before placing a digitizing order lets you quote clients accurately and check delivered files. Key variables: design size, fill coverage, stitch density, and specialty elements like 3D puff or appliqué.',
                'hero_image_alt'   => 'Embroidery stitch count estimation for custom hats and jackets',
                'category'         => 'Digitizing Tips',
                'tags'             => 'stitch count, embroidery, hats, jackets, cap front, left chest, digitizing',
                'meta_title'       => 'How to Calculate Stitch Counts for Custom Hats and Jackets',
                'meta_description' => 'Learn to estimate embroidery stitch counts before digitizing. Use this simple formula to quote clients, plan production time, and evaluate delivered files.',
                'content'          => '<p>Before you even send artwork to a digitizer, you can get a surprisingly accurate stitch count estimate by factoring in design size, how much of that area is solid fill, stitch density, and whether anything like 3D puff or appliqué is involved. Left chest logos typically land between 8,000 and 15,000 stitches. Cap fronts usually come in at 5,000 to 12,000. Full jacket backs? Anywhere from 50,000 to 250,000, depending on how complex the design is.</p><h2>Why This Skill Pays for Itself</h2><p>Every embroidery shop owner knows the situation. A client sends over a logo and asks for a quote on 200 embroidered hats — and they need the number today. Your decoration cost depends on machine time, machine time depends on stitch count, and you don\'t have the digitized file yet. The shops that quote confidently aren\'t guessing — they\'ve learned to estimate from the artwork alone.</p><h2>The Core Formula</h2><p>For fill stitch areas: Stitch count = (Fill area in mm²) ÷ (Density in mm) × (Average stitch length in mm). Standard fill at 0.40mm density with a 4mm average stitch length gives 10 stitches per mm². That\'s the number worth memorizing.</p><p>For outlines and running stitch details: divide the total path length in mm by your average stitch length. At 2mm per stitch, that\'s 0.5 stitches per mm.</p><h2>Figuring Out Fill Coverage</h2><p><strong>High fill (70–90%):</strong> Mostly solid designs — big filled text, thick color blocks.</p><p><strong>Medium fill (40–60%):</strong> Mix of filled elements and outlines with meaningful open space.</p><p><strong>Low fill (10–30%):</strong> Outline-heavy work, primarily running stitch, lots of negative space.</p><h2>Stitch Count Ranges by Placement</h2><h3>Cap Front</h3><ul><li>Simple outline or single-color text: 3,000–6,000</li><li>Standard multi-color logo: 6,000–12,000</li><li>Complex dense logo: 12,000–20,000</li><li>3D puff elements: add 20–40% to fill counts</li></ul><h3>Left Chest</h3><ul><li>Simple text or single element: 5,000–10,000</li><li>Standard corporate logo, 3–5 colors: 8,000–18,000</li><li>Complex logo with fine detail: 15,000–30,000</li></ul><h3>Full Front or Full Back</h3><ul><li>Simple outline design: 20,000–50,000</li><li>Medium complexity graphic: 50,000–120,000</li><li>Dense detailed design: 120,000–250,000</li></ul><h3>Jacket Back</h3><p>Same size as full back, but commonly executed in appliqué. A design that would be 180,000 stitches as pure fill might drop to 55,000–70,000 with appliqué construction.</p><h2>Quick Estimation Method</h2><ol><li>Lock in actual embroidered dimensions.</li><li>Calculate bounding area: width × height in mm.</li><li>Estimate coverage: High = 80%, Medium = 50%, Low = 20%.</li><li>Fill area = bounding area × coverage.</li><li>Multiply by 10 stitches per mm².</li><li>Add 20–30% for running stitch allowance.</li></ol><p>Example: 90 × 70mm logo at medium fill (50%) = 3,150mm² fill area × 10 = 31,500 stitches + 25% = roughly 39,000 stitches.</p><h2>Checking Delivered Files</h2><p>Compare the actual stitch count to your estimate. Significantly higher = potentially over-dense. Close to estimate = well-digitized. Significantly lower = potentially under-dense with gappy fill areas.</p><h2>Production Planning Benchmarks</h2><ul><li>600 SPM: 1,000 stitches ≈ 1 minute</li><li>800 SPM: 1,000 stitches ≈ 0.75 minutes</li><li>1,000 SPM: 1,000 stitches ≈ 0.6 minutes</li></ul><p>For multi-head runs, multiply per-piece time by piece count and divide by active heads to get total machine time.</p>',
            ],
            [
                'title'            => 'Flat Rate vs. Stitch Count Pricing — Which Is Better for Your Embroidery Shop?',
                'slug'             => 'flat-rate-vs-stitch-count-pricing-which-is-better-for-your-embroidery-shop',
                'excerpt'          => 'Most shops fall into a pricing model by accident. This article breaks down flat rate vs. stitch count pricing — what each model actually costs, where each one fails, and how to know which one fits your shop.',
                'hero_image_alt'   => 'Flat rate vs stitch count pricing for embroidery digitizing',
                'category'         => 'Business Tips',
                'tags'             => 'pricing, flat rate, stitch count, digitizing, embroidery business',
                'meta_title'       => 'Flat Rate vs. Stitch Count Pricing for Embroidery Shops',
                'meta_description' => 'Flat rate or per-stitch pricing — which digitizing model fits your shop? We break down the real costs, tradeoffs, and which setup works best by design mix.',
                'content'          => '<p>Most shops end up in a pricing model by accident. They found a digitizing service early on, it worked well enough, and they never really stopped to ask whether the pricing structure was doing them any favors. This article is that question.</p><p>There are two ways digitizing services charge you. Flat rate: one price per job, typically $10–$25, no matter how complicated the design. Or per-stitch: a rate per thousand stitches, usually $3–$6 on quality manual work.</p><h2>What You\'re Really Buying with Flat Rate</h2><p>Flat rate sells simplicity as much as it sells digitizing. One price, every job, no surprises on the invoice. If your bread and butter is 20 corporate left-chest logos a month — polo shirts, 8,000 to 12,000 stitches, nothing wild — flat rate probably makes complete sense. Predictable spend, easy to bake into client pricing, no invoice that comes back higher than expected.</p><p>The problem shows up at the edges. Every flat rate service has a complexity ceiling. Send a 90,000-stitch jacket back to a $15-per-design service and one of three things happens: they charge extra, the quality suffers, or they tell you it\'s out of scope.</p><h2>What You\'re Actually Paying For with Stitch Count Pricing</h2><p>Per-stitch pricing is proportional. A 10,000-stitch left chest logo costs less than a 50,000-stitch athletic design. It reflects the actual work involved. The friction is variability — if you don\'t estimate stitch counts before ordering, invoices can come in higher than expected.</p><p>Once you know your numbers, per-stitch pricing becomes very transparent. You can estimate cost before placing the order.</p><h2>What Determines Which Model Works for You</h2><p>It comes down to your design mix. Shops running simple, predictable work do fine on flat rate. Corporate uniform programs, school apparel, basic team setups — flat rate is probably cheaper and easier to manage.</p><p>Shops with real design variety will end up either overpaying on flat rate for simple work or accepting compromised quality on complex jobs.</p><p>Example monthly output: 15 simple left-chest logos (~10k stitches each), 5 mid-complexity logos (~25k stitches), 2 jacket backs (~120k stitches).</p><p>Flat rate at $15: ~$330 — but jacket backs trigger surcharges or quality issues, so that number isn\'t real.</p><p>Stitch count at $4/1k: $600 (left chest) + $500 (mid) + $960 (jacket backs) = $2,060.</p><p>Split the work: simple logos to flat rate ($225) + complex on per-stitch ($1,460) = $1,685 with better quality on the jobs that need it most.</p><h2>Questions Worth Asking</h2><p>Are you satisfied with quality on your most complex jobs? Do you know your typical stitch counts? Are you sending complex work to a flat rate service out of habit despite quality concerns? The cost shows up in reprints and client complaints, not on the digitizing invoice.</p><p>Neither model is universally better. The right one fits the work you actually do.</p>',
            ],
            [
                'title'            => 'How Outsourcing Digitizing Increases Daily Machine Run Time',
                'slug'             => 'how-outsourcing-digitizing-increases-daily-machine-run-time',
                'excerpt'          => 'A machine sitting still is the most expensive thing in your shop. This article breaks down where idle time actually comes from — and how outsourcing digitizing fixes more of it than most shop owners expect.',
                'hero_image_alt'   => 'Embroidery machine run time and production efficiency through outsourced digitizing',
                'category'         => 'Business Tips',
                'tags'             => 'outsourcing, digitizing, machine run time, production efficiency, embroidery business',
                'meta_title'       => 'How Outsourcing Digitizing Increases Machine Run Time',
                'meta_description' => 'A stopped machine is your biggest cost. Learn how outsourcing digitizing cuts idle time, thread breaks, and morning waits to maximize your daily machine capacity.',
                'content'          => '<p>The most expensive thing in an embroidery shop isn\'t thread or hoops or even the machines themselves. It\'s a machine sitting still.</p><p>When a 15-head isn\'t running, you\'re losing somewhere between $40 and $70 per head per hour. Real money leaving the table every time a needle stops moving — and it happens more often than most shop owners realize until they actually sit down and track it.</p><h2>What Your Machines Are Doing When They\'re Not Running</h2><p>Ask most shop owners what\'s causing their downtime and they\'ll say changeovers, staffing, scheduling. Those are real. But spend a day watching the production floor and you\'ll see a different list.</p><p>Thread break — operator walks over, rethread, find where it broke, re-hoop if needed, restart. That\'s 5–10 minutes gone, six to ten times a day, per head.</p><p>Trim buildup from sloppy pathing, so somebody stands there babysitting the machine between elements instead of loading the next frame on a different head.</p><p>The morning wait — digitizing isn\'t done yet, so nothing starts at shift open. Maybe 30 minutes. Maybe an hour. Every day.</p><p>None of that shows up as a line item. It just quietly eats your machine capacity.</p><h2>The In-House Digitizing Math Most Shops Don\'t Run</h2><p>If your lead operator spends two hours a day digitizing, and your machines run at $45 per head per hour on a 10-head, that\'s $900 in potential machine revenue that never got captured. Every day. Five days a week: $4,500. Per month: $18,000 — not as a hard loss, as capacity that never got used.</p><p>In-house digitizers aren\'t touching hundreds of designs a month the way a specialist service does. That skill gap shows up as more thread breaks, more trimming problems, more re-runs on difficult fabrics.</p><h2>How Cleaner Files Change What Your Machines Can Do</h2><p>When a file throws frequent thread breaks, one operator can manage maybe 4–6 heads before something gets missed. When a file runs cleanly, one operator can manage 10, 12, sometimes 15 heads.</p><p>On a 15-head running an 8-hour shift, the gap between 5-head and 12-head effective utilization is around 400–500 machine hours per month.</p><p>Cutting from 6 thread breaks per day to 2 — on a 10-head, at 5–8 minutes per break — recovers 200–400 minutes of run time daily. That\'s 3–6 extra production hours without changing anything else.</p><h2>Tracking What You\'re Actually Losing</h2><p>Spend a week recording actual needle-moving hours. Most shops find real utilization between 50–65% of available shift hours. Well-run operations hit 80–90%.</p><p>In most shops, 30–50% of non-changeover idle time has some connection to digitizing quality or availability. Compare that against what outsourced digitizing costs at your volume — the recovered machine time is worth significantly more, often by a factor of 5–10.</p><h2>Making the Transition Work</h2><p>Get all digitizing requests to your service by end of business each day, with files returned before the next morning\'s shift starts. That one change eliminates the reactive wait completely.</p><p>Build a file library as designs come back. Most jobs get rerun — pay for digitizing once per design, not every time the client reorders.</p><p>The hours that used to go toward in-house digitizing go somewhere else: more floor time, more heads per operator, better quality control. That reallocation is usually where shops feel the biggest difference.</p>',
            ],
        ];

        foreach ($articles as $a) {
            if (\App\Models\Blog::where('title', $a['title'])->exists()) {
                continue;
            }
            $slug = $a['slug'];
            if (\App\Models\Blog::where('slug', $slug)->exists()) {
                $slug .= '-2';
            }
            \App\Models\Blog::create([
                'title'            => $a['title'],
                'slug'             => $slug,
                'excerpt'          => $a['excerpt'],
                'content'          => $a['content'],
                'hero_image'       => null,
                'hero_image_alt'   => $a['hero_image_alt'],
                'author_name'      => '1 Dollar Digitizing',
                'category'         => $a['category'],
                'tags'             => $a['tags'],
                'status'           => 'draft',
                'meta_title'       => $a['meta_title'],
                'meta_description' => $a['meta_description'],
                'date'             => $today,
            ]);
            $inserted[] = $a['title'];
        }

        $msg = count($inserted)
            ? 'Inserted: ' . implode(', ', $inserted)
            : 'All articles already exist.';

        return redirect('/v/blogs')->with('success', $msg);
    });
});

Route::get('/team', [TeamAuthController::class, 'showLogin']);
Route::get('/team/index.php', [TeamAuthController::class, 'showLogin']);
Route::post('/team/login', [TeamAuthController::class, 'login']);
Route::get('/team/logout.php', [TeamAuthController::class, 'logout']);

Route::middleware('team.auth')->group(function () {
    Route::get('/team/welcome.php', [TeamDashboardController::class, 'index'])->name('team.dashboard');
    Route::get('/team/queues/{queue}', [TeamOrdersController::class, 'queue']);
    Route::get('/team/under-process-orders.php', [TeamOrdersController::class, 'underProcess']);
    Route::post('/team/orders/{order}/working', [TeamOrdersController::class, 'saveWorking']);
    Route::get('/team/disapproved-orders.php', [TeamOrdersController::class, 'compatibilityQueueRedirect'])->defaults('queue', 'disapproved-orders');
    Route::get('/team/view-quotes.php', [TeamOrdersController::class, 'compatibilityQueueRedirect'])->defaults('queue', 'quotes');
    Route::get('/team/under-process-quick-orders.php', [TeamOrdersController::class, 'compatibilityQueueRedirect'])->defaults('queue', 'quick-quotes');

    Route::get('/team/view-order-detail.php', [TeamOrderDetailController::class, 'show']);
    Route::get('/team/orders/{order}/detail/{mode?}', [TeamOrderDetailController::class, 'showByRoute']);
    Route::post('/team/order-detail/comments', [TeamOrderDetailController::class, 'saveComment']);
    Route::post('/team/team-comments/{comment}/delete', [TeamOrderDetailController::class, 'deleteComment']);
    Route::post('/team/order-detail/upload', [TeamOrderDetailController::class, 'uploadAttachment']);
    Route::get('/team/attachments/{attachment}/download', [TeamOrderDetailController::class, 'downloadAttachment']);
    Route::get('/team/attachments/{attachment}/preview', [TeamOrderDetailController::class, 'previewAttachment']);
    Route::get('/team/attachments/{attachment}/preview/raw', [TeamOrderDetailController::class, 'previewAttachment'])->defaults('raw', 1);
    Route::post('/team/attachments/{attachment}/delete', [TeamOrderDetailController::class, 'deleteAttachment']);
    Route::get('/team/team_get_design_info_file.php', [TeamDesignInfoController::class, 'download']);
    Route::post('/team/order-detail/complete', [TeamOrderDetailController::class, 'complete']);
    Route::post('/team/orders/{order}/submit-quote', [FreelanceQuoteController::class, 'submit']);
    Route::get('/team/my-jobs', [TeamJobHistoryController::class, 'index']);
    Route::post('/team/request-payment', [FreelancePaymentRequestController::class, 'store']);

    Route::get('/team/view-order-quick-detail.php', [TeamQuickQuoteController::class, 'show']);
    Route::get('/team/quick-quotes/{order}/detail', [TeamQuickQuoteController::class, 'showByRoute']);
    Route::post('/team/quick-order/comments', [TeamQuickQuoteController::class, 'saveComment']);
    Route::post('/team/quick-comments/{comment}/delete', [TeamQuickQuoteController::class, 'deleteComment']);
    Route::post('/team/quick-order/upload', [TeamQuickQuoteController::class, 'uploadAttachment']);
    Route::get('/team/quick-attachments/{attachment}/download', [TeamQuickQuoteController::class, 'downloadAttachment']);
    Route::get('/team/quick-attachments/{attachment}/preview', [TeamQuickQuoteController::class, 'previewAttachment']);
    Route::get('/team/quick-attachments/{attachment}/preview/raw', [TeamQuickQuoteController::class, 'previewAttachment'])->defaults('raw', 1);
    Route::post('/team/quick-attachments/{attachment}/delete', [TeamQuickQuoteController::class, 'deleteAttachment']);
    Route::post('/team/quick-order/complete', [TeamQuickQuoteController::class, 'complete']);
});

Route::middleware(['team.auth', 'supervisor.auth'])->group(function () {
    Route::match(['get', 'post'], '/team/manage-team.php', [TeamSupervisorController::class, 'members']);
    Route::get('/team/team-member-detail.php', [TeamSupervisorController::class, 'memberDetail']);
    Route::get('/team/review-queue.php', [TeamSupervisorController::class, 'reviewQueue'])->name('supervisor.review-queue');
    Route::get('/team/create-team.php', [TeamSupervisorController::class, 'memberForm']);
    Route::post('/team/create-team.php', [TeamSupervisorController::class, 'memberSave']);
    Route::get('/team/assign-order.php', [TeamSupervisorController::class, 'assignForm']);
    Route::post('/team/assign-order.php', [TeamSupervisorController::class, 'assignSave']);
    Route::post('/team/review-order.php', [TeamSupervisorController::class, 'markReviewed']);
    Route::post('/team/supervisor/orders/{order}/approve', [TeamSupervisorController::class, 'supervisorApprove']);
    Route::post('/team/supervisor/orders/{order}/disapprove', [TeamSupervisorController::class, 'supervisorDisapprove']);
    Route::post('/team/supervisor/orders/{order}/accept', [TeamSupervisorController::class, 'acceptJob']);
    Route::get('/team/supervisor/assignments', [TeamSupervisorController::class, 'assignmentMonitor']);
    Route::post('/team/supervisor/orders/{order}/accept-quote', [TeamSupervisorController::class, 'acceptFreelanceQuote']);
    Route::post('/team/supervisor/orders/{order}/pull-back', [TeamSupervisorController::class, 'pullBackJob']);
});

Route::get('/uploads/{path}', [UploadController::class, 'show'])->where('path', '.*');
