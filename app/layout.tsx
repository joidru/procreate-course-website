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
  themeColor: '#f5f1ea',
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html
      lang="ru"
      className={`light ${inter.variable} ${spaceGrotesk.variable} bg-background`}
    >
      <body className="antialiased font-sans">
        {children}
        {process.env.NODE_ENV === 'production' && <Analytics />}
      </body>
    </html>
  )
}
