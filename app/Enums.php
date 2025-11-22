<?php

namespace App;

class TransactionStatus
{
    const WAITING_PAYMENT = 1;
    const WAITING_CONFIRM = 2;
    const PROCESSING      = 3;
    const SHIPPING        = 4;
    const SUCCESS         = 5;
    const CANCELLED       = 6;
    const REJECTED        = 7;
}
