<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\OrderWasCreated' => [
            'App\Listeners\SendSmsOrderCreateNotitficationRequest',
            'App\Listeners\CreateOrderInvoice',
            'App\Listeners\SendOrderCreateRequestToJooleh',
            'App\Listeners\SendOrderNotificationEmailToUser',
        ],
        'App\Events\DeliveryBoyWasCreated' => [
            'App\Listeners\SendDeliveryBoyCreateRequestToJooleh',
        ],
        'App\Events\DeliveryBoyWasUpdated' => [
            'App\Listeners\SendDeliveryBoyUpdateRequestToJooleh',
        ],
        'App\Events\UserWasCreated'=> [
            'App\Listeners\AssignGroupToUser',
            'App\Listeners\AssignMembershipLevelToUser',
            'App\Listeners\GenerateReferralCouponCodeOfUser',
            'App\Listeners\SendWelcomeEmailToNewUser',
        ],
        'App\Events\OrderWasSettled'=> [
            'App\Listeners\CreditLoyaltyPoints',
            'App\Listeners\UpdateUserMembership',
        ],
        'App\Events\OrderWasCancelled'=>[
          'App\Listeners\SendOrderCancelNotification',
          'App\Listeners\SendOrderCancelRequestToJooleh',
          'App\Listeners\ReStockOrderItems'
        ],
        'App\Events\OrderWasDispatched'=>[
            'App\Listeners\SendOrderDispatchEmailToUser',
            'App\Listeners\SendOrderUpdateRequestToJooleh',
        ],
        'App\Events\OrderStatusWasUpdated'=>[
            'App\Listeners\SendOrderRequestToJooleh',
        ],
        'App\Events\UserWasRegistered'=> [
            'App\Listeners\SendOTPVerificationToUser',
            'App\Listeners\AssignGroupToUser',
            'App\Listeners\AssignMembershipToUser',
            'App\Listeners\GenerateReferralCouponOfUser',
            'App\Listeners\SendUserDataToIntercom',
            'App\Listeners\SendWelcomeEmailToUser',
        ],
        'App\Events\OrderWasCreatedByUser'=> [
            'App\Listeners\SendOrderSMSNotificationToUser',
            'App\Listeners\UpdateStock',
            'App\Listeners\SendOrderEmailNotificationToUser',
//            'App\Listeners\CheckIfItemsAreInLowStock',
           'App\Listeners\SendNewOrderRequestToJooleh'
        ],
        'App\Events\StockWasUpdated'=> [
            'App\Listeners\UpdateItemStatus',
        ],
        'App\Events\MealWasDisabled'=> [
          'App\Listeners\UpdateComboStatus'
        ],
        'App\Events\SyncStockWithTorqusEvent'=> [
          'App\Listeners\SyncStock'
        ],
        'App\Events\ReferralSuccess'=>[
          'App\Listeners\SendSmsOnReferralSuccess'
        ]

    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
