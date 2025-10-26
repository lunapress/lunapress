import { createRoot } from 'react-dom/client'
import { StrictMode } from 'react'
import { Notice } from './ui'
import { Selectors } from './constants'

document.querySelectorAll(Selectors.Notice).forEach((element) => {
    createRoot(element).render(
        <StrictMode>
            <Notice />
        </StrictMode>,
    )
})
