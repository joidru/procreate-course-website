'use client'

import { useEffect, useState } from 'react'
import { Menu, X, Sun, Moon } from 'lucide-react'

function ThemeToggle() {
  const [theme, setTheme] = useState<'light' | 'dark'>('dark')

  useEffect(() => {
    const current = document.documentElement.classList.contains('light')
      ? 'light'
      : 'dark'
    setTheme(current)
  }, [])

  function toggle() {
    const next = theme === 'dark' ? 'light' : 'dark'
    const root = document.documentElement
    root.classList.remove('light', 'dark')
    root.classList.add(next)
    try {
      localStorage.setItem('theme', next)
    } catch {}
    setTheme(next)
  }

  return (
    <button
      type="button"
      onClick={toggle}
      aria-label={theme === 'dark' ? 'Светлая тема' : 'Тёмная тема'}
      className="flex h-11 w-11 items-center justify-center rounded-full text-foreground transition-colors hover:bg-accent"
    >
      {theme === 'dark' ? (
        <Sun className="h-5 w-5" />
      ) : (
        <Moon className="h-5 w-5" />
      )}
    </button>
  )
}

const nav = [
  { href: '#program', label: 'Программа' },
  { href: '#works', label: 'Работы' },
  { href: '#included', label: 'Что входит' },
  { href: '#author', label: 'Автор' },
]

export function SiteHeader() {
  const [open, setOpen] = useState(false)

  return (
    <header className="sticky top-0 z-50 border-b border-border/60 bg-background/80 backdrop-blur-md">
      <div className="mx-auto flex h-16 max-w-6xl items-center justify-between px-5 md:px-8">
        <a
          href="#top"
          className="font-display text-base font-bold tracking-tight text-foreground"
        >
          Евгения Гагарина
        </a>

        <nav className="hidden items-center gap-8 md:flex">
          {nav.map((item) => (
            <a
              key={item.href}
              href={item.href}
              className="text-sm text-muted-foreground transition-colors hover:text-foreground"
            >
              {item.label}
            </a>
          ))}
        </nav>

        <div className="flex items-center gap-1 md:gap-2">
          <ThemeToggle />
          <a
            href="#pricing"
            className="hidden rounded-full bg-primary px-5 py-2.5 text-sm font-medium text-primary-foreground transition-opacity hover:opacity-90 md:inline-flex"
          >
            Купить курс
          </a>
          <button
            type="button"
            aria-label={open ? 'Закрыть меню' : 'Открыть меню'}
            aria-expanded={open}
            onClick={() => setOpen((v) => !v)}
            className="flex h-11 w-11 items-center justify-center rounded-full text-foreground md:hidden"
          >
            {open ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
          </button>
        </div>
      </div>

      {open && (
        <div className="border-t border-border/60 bg-background md:hidden">
          <nav className="mx-auto flex max-w-6xl flex-col px-5 py-3">
            {nav.map((item) => (
              <a
                key={item.href}
                href={item.href}
                onClick={() => setOpen(false)}
                className="py-3 text-base text-foreground"
              >
                {item.label}
              </a>
            ))}
            <a
              href="#pricing"
              onClick={() => setOpen(false)}
              className="mt-2 inline-flex items-center justify-center rounded-full bg-primary px-5 py-3 text-base font-medium text-primary-foreground"
            >
              Купить курс
            </a>
          </nav>
        </div>
      )}
    </header>
  )
}
