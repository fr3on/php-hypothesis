<?php

declare(strict_types=1);

namespace Fr3on\Hypothesis\Engine;

/**
 * Health checks to catch pathological test behaviors.
 */
enum HealthCheck: string
{
    /** Test generation or execution is slower than the deadline. */
    case TOO_SLOW = 'too_slow';

    /** Rejections via assume() or ->filter() exceed 90%. */
    case FILTER_TOO_MUCH = 'filter_too_much';

    /** The internal draw buffer was exceeded (shape too complex). */
    case OVERRUN = 'overrun';

    /** The generated value exceeds the reasonable size limit. */
    case DATA_TOO_LARGE = 'data_too_large';
}
