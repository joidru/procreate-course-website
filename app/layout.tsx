import { Analytics } from '@vercel/analytics/next'
import type { Metadata, Viewport } from 'next'
import { Inter, Space_Grotesk } from 'next/font/google'
import './globals.css'

const inter = Inter({
  subsets: ['latin', 'cyrillic'],
  variable: '--font-inter',
  display: 'swap',
})

const spaceGrotesk = Space_Grotesk({
  subsets: ['latin'],
  variable: '--font-space-grotesk',
  display: 'swap',
})

export const metadata: Metadata = {
  title: 'Основы рисования в Procreate — курс Евгении Гагариной',
  description:
    'Онлайн-курс «Основы рисования в Procreate» от художницы Евгении Гагариной. Форма и объём, перспектива, светотень, цвет, композиция и фигура человека. 30 уроков, доступ навсегда.',
  generator: 'v0.app',
  openGraph: {
    title: 'Основы рисования в Procreate — курс Евгении Гагариной',
    description:
      'Научитесь рисовать в Procreate с нуля: форма, перспектива, светотень, цвет, композиция и фигура человека. 30 уроков, кисти, исходники и текстуры.',
    type: 'website',
    locale: 'ru_RU',
  },
}

export const viewport: Viewport = {
  width: 'device-width',
  initialScale: 1,
  maximumScale: 1,
  themeColor: [
    { media: '(prefers-color-scheme: light)', color: '#f5f1ea' },
    { media: '(prefers-color-scheme: dark)', color: '#26221f' },
  ],
}

const themeScript = `
(function () {
  try {
    var stored = localStorage.getItem('theme');
    var theme = stored || 'dark';
    var root = document.documentElement;
    root.classList.remove('light', 'dark');
    root.classList.add(theme);
  } catch (e) {}
})();
`

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html
      lang="ru"
      className={`dark ${inter.variable} ${spaceGrotesk.variable} bg-background`}
      suppressHydrationWarning
    >
      <head>
        <script dangerouslySetInnerHTML={{ __html: themeScript }} />
      </head>
      <body className="antialiased font-sans">
        {children}
        {process.env.NODE_ENV === 'production' && <Analytics />}
      </body>
    </html>
  )
}
