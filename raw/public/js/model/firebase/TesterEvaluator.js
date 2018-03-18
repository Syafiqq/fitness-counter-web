function evaluatorIllinois(gender, elapsed)
{
    var result = null;
    gender     = String(gender);
    elapsed    = Number(elapsed) / (1000.0);
    if (gender === 'l')
    {
        // @formatter:off
        if      (elapsed < 15.2)    result = 20.0;
        else if (elapsed <= 16.1)   result = 16.0;
        else if (elapsed <= 18.1)   result = 12.0;
        else if (elapsed <= 19.3)   result = 8.0;
        else                        result = 4.0;
        // @formatter:on
    }
    else if (gender === 'p')
    {
        // @formatter:off
        if      (elapsed < 17.0)    result = 20.0;
        else if (elapsed <= 17.9)   result = 16.0;
        else if (elapsed <= 21.7)   result = 12.0;
        else if (elapsed <= 23.0)   result = 8.0;
        else                        result = 4.0;
        // @formatter:on
    }
    return result;
}

function evaulatorPushUp(gender, counter)
{
    var result = null;
    gender     = String(gender);
    counter    = Number(counter);
    if (gender === 'l')
    {
        // @formatter:off
        if      (counter >  46) result = 10.0;
        else if (counter >= 36) result = 8.0;
        else if (counter >= 26) result = 6.0;
        else if (counter >= 16) result = 4.0;
        else                    result = 2.0;
        // @formatter:on
    }
    else if (gender === 'p')
    {
        // @formatter:off
        if      (counter >  35) result = 10.0;
        else if (counter >= 25) result = 8.0;
        else if (counter >= 15) result = 6.0;
        else if (counter >= 5)  result = 4.0;
        else                    result = 2.0;
        // @formatter:on
    }
    return result;
}

function evaluatorRun(gender, elapsed)
{
    var result = null;
    gender     = String(gender);
    elapsed    = Number(elapsed) / (60.0 * 1000.0);
    if (gender === 'l')
    {
        // @formatter:off
        if      (elapsed <  6.10)   result = 30.0;
        else if (elapsed <= 6.40)   result = 25.0;
        else if (elapsed <= 7.35)   result = 20.0;
        else if (elapsed <= 8.34)   result = 15.0;
        else                        result = 10.0;
        // @formatter:on
    }
    else if (gender === 'p')
    {
        // @formatter:off
        if      (elapsed  <  8.21)   result = 30.0;
        else if (elapsed <=  9.30)   result = 25.0;
        else if (elapsed <= 10.50)   result = 20.0;
        else if (elapsed <= 12.15)   result = 15.0;
        else                         result = 10.0;
        // @formatter:on
    }
    return result;
}

function evaluatorSitUp(gender, counter)
{
    var result = null;
    gender     = String(gender);
    counter    = Number(counter);
    if (gender === 'l')
    {
        // @formatter:off
        if      (counter >  41) result = 10.0;
        else if (counter >= 30) result = 8.0;
        else if (counter >= 21) result = 6.0;
        else if (counter >= 10) result = 4.0;
        else                    result = 2.0;
        // @formatter:on
    }
    else if (gender === 'p')
    {
        // @formatter:off
        if      (counter >  28) result = 10.0;
        else if (counter >= 20) result = 8.0;
        else if (counter >= 10) result = 6.0;
        else if (counter >= 3)  result = 4.0;
        else                    result = 2.0;
        // @formatter:on
    }
    return result;
}

function evaluatorThrowingBall(gender, counter)
{
    var result = null;
    gender     = String(gender);
    counter    = Number(counter);
    if (gender === 'l')
    {
        // @formatter:off
        if      (counter >  40) result = 20.0;
        else if (counter >= 35) result = 16.0;
        else if (counter >= 25) result = 12.0;
        else if (counter >= 15) result = 8.0;
        else                    result = 4.0;
        // @formatter:on
    }
    else if (gender === 'p')
    {
        // @formatter:off
        if      (counter >  35) result = 20.0;
        else if (counter >= 30) result = 16.0;
        else if (counter >= 20) result = 12.0;
        else if (counter >= 10) result = 8.0;
        else                    result = 4.0;
        // @formatter:on
    }
    return result;
}

function evaluatorVerticalJump(gender, counter)
{
    var result = null;
    gender     = String(gender);
    counter    = Number(counter);
    if (gender === 'l')
    {
        // @formatter:off
        if      (counter >  72) result = 10.0;
        else if (counter >= 60) result = 8.0;
        else if (counter >= 50) result = 6.0;
        else if (counter >= 39) result = 4.0;
        else                    result = 2.0;
        // @formatter:on
    }
    else if (gender === 'p')
    {
        // @formatter:off
        if      (counter >  49) result = 10.0;
        else if (counter >= 39) result = 8.0;
        else if (counter >= 31) result = 6.0;
        else if (counter >= 23) result = 4.0;
        else                    result = 2.0;
        // @formatter:on
    }
    return result;
}
