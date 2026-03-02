import { __, _n, _nx, _x } from '@wordpress/i18n'
import { DOMAIN } from '@/const'

const TEXT = 'text'

__(TEXT, DOMAIN)

export const Notice = () => {
    _x('text with context', 'context', DOMAIN)

    return (
        <>
            <div className="">{_n('single', 'plural', 3, DOMAIN)}</div>
        </>
    )
}

_nx('single2', 'plural2', 4, 'context2', DOMAIN)