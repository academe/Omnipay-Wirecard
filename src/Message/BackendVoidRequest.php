<?php

/**
 * A transaction can be voided before it is transferredby the financial
 * merchant, usually at midnight the day the transaction was captured,
 * or anytime before it is captured.
 * If the payment is just authorised, then it can be voided givem just
 * the orderNumber. This is the "approveReversal" command.
 * Once captured (and before tranferred by the financial institution)
 * it can be voided using the "depositReversal" command. This command
 * needs both the orderNumber and the paymentNumber.
 * Note this also means that a transaction with multiple payment parts
 * can have just some parts voided.
 * After that, to undo the transaction a refund must be issued.
 */


//BackendVoidRequest
