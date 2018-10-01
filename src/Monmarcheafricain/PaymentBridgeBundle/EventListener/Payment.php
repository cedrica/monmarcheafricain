<?php
/**
 * Created by PhpStorm.
 * User: cedric.leumaleu
 * Date: 01.10.2018
 * Time: 18:38
 */
namespace App\Monmarcheafricain\PaymentBridgeBundle\EventListener;

use PaymentSuite\PaymentCoreBundle\Event\PaymentOrderLoadEvent;
use PaymentSuite\PaymentCoreBundle\Event\PaymentOrderCreatedEvent;
use PaymentSuite\PaymentCoreBundle\Event\PaymentOrderDoneEvent;
use PaymentSuite\PaymentCoreBundle\Event\PaymentOrderSuccessEvent;
use PaymentSuite\PaymentCoreBundle\Event\PaymentOrderFailEvent;

/**
 * Payment event listener
 *
 * This listener is enabled whatever the payment method is.
 */
class Payment
{
    /**
     * On payment order load event
     *
     * @param PaymentOrderLoadEvent $paymentOrderLoadEvent Payment Order Load event
     */
    public function onPaymentOrderLoad(PaymentOrderLoadEvent $paymentOrderLoadEvent)
    {
        /*
         * Your code for this event
         */
    }

    /**
     * On payment order created event
     *
     * @param PaymentOrderCreatedEvent $paymentOrderCreatedEvent Payment Order Created event
     */
    public function onPaymentOrderCreated(PaymentOrderCreatedEvent $paymentOrderCreatedEvent)
    {
        /*
         * Your code for this event
         */
    }

    /**
     * On payment done event
     *
     * @param PaymentOrderDoneEvent $paymentOrderDoneEvent Payment Order Done event
     */
    public function onPaymentDone(PaymentOrderDoneEvent $paymentOrderDoneEvent)
    {
        /*
         * Your code for this event
         */
    }

    /**
     * On payment success event
     *
     * @param PaymentOrderSuccessEvent $paymentOrderSuccessEvent Payment Order Success event
     */
    public function onPaymentSuccess(PaymentOrderSuccessEvent $paymentOrderSuccessEvent)
    {
        /*
         * Your code for this event
         */
    }

    /**
     * On payment fail event
     *
     * @param PaymentOrderFailEvent $paymentOrderFailEvent Payment Order Fail event
     */
    public function onPaymentFail(PaymentOrderFailEvent $paymentOrderFailEvent)
    {
        /*
         * Your code for this event
         */
    }
}