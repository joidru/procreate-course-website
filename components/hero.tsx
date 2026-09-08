import { ArrowRight, Sparkles } from 'lucide-react'
import { course } from '@/lib/course'

export function Hero() {
  return (
    <section id="top" className="relative overflow-hidden">
      <div className="mx-auto max-w-6xl px-5 pt-12 pb-6 md:px-8 md:pt-20 md:pb-16">
        <div className="grid items-center gap-10 md:grid-cols-2 md:gap-12">
          <div className="flex flex-col items-start">
            <span className="inline-flex items-center gap-2 rounded-full border border-border bg-card px-4 py-1.5 text-xs font-medium text-muted-foreground">
              <Sparkles className="h-3.5 w-3.5 text-primary" />
              Онлайн-курс по рисованию на iPad
            </span>

            <h1 className="mt-6 text-balance font-display text-4xl font-bold leading-[1.05] tracking-tight text-foreground sm:text-5xl md:text-6xl">
              Основы рисования в{' '}
              <span className="text-primary">Procreate</span>
            </h1>

            <p className="mt-5 max-w-md text-pretty text-base leading-relaxed text-muted-foreground md:text-lg">
              {course.subtitle}
            </p>

            <div className="mt-8 flex flex-wrap items-baseline gap-3">
              <span className="font-display text-4xl font-bold text-foreground">
                {course.priceNew}
              </span>
              <span className="text-lg text-muted-foreground line-through">
                {course.priceOld}
              </span>
              <span className="rounded-full bg-accent px-3 py-1 text-xs font-medium text-accent-foreground">
                −60%
              </span>
            </div>
            <p className="mt-2 text-xs text-muted-foreground">
              {course.discountNote}
            </p>

            <div className="mt-7 flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
              <a
                href="#pricing"
                className="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-7 py-3.5 text-base font-medium text-primary-foreground transition-opacity hover:opacity-90"
              >
                Записаться на курс
                <ArrowRight className="h-4 w-4" />
              </a>
              <a
                href="#works"
                className="inline-flex items-center justify-center rounded-full border border-border bg-card px-7 py-3.5 text-base font-medium text-foreground transition-colors hover:bg-secondary"
              >
                Смотреть работы
              </a>
            </div>
          </div>

          <div className="relative">
            <div className="absolute -inset-4 -z-10 rounded-[2rem] bg-accent/60 blur-2xl" />
            <div className="overflow-hidden rounded-[1.75rem] border border-border bg-card p-2 shadow-xl shadow-foreground/5">
              <img
                src="/artwork-hero.png"
                alt="Цифровой портрет, нарисованный в Procreate — пример работы с курса"
                className="aspect-square w-full rounded-[1.25rem] object-cover"
              />
            </div>
          </div>
        </div>
      </div>
    </section>
  )
}
