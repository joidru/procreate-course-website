import { Check, ArrowRight } from 'lucide-react'
import { course } from '@/lib/course'

const perks = [
  '30 уроков и доступ навсегда',
  'Кисти, исходники и текстуры',
  '45 референсов для практики',
  'Обратная связь и чат учеников',
]

export function Pricing() {
  return (
    <section id="pricing" className="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
      <div className="overflow-hidden rounded-[2rem] border border-border bg-card">
        <div className="grid md:grid-cols-2">
          <div className="relative hidden md:block">
            <img
              src="/artwork-color.png"
              alt="Красочный этюд, нарисованный в Procreate"
              className="h-full w-full object-cover"
            />
          </div>

          <div className="p-7 md:p-12">
            <p className="text-sm font-medium text-primary">Стоимость</p>
            <h2 className="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">
              Начните рисовать уже сегодня
            </h2>

            <div className="mt-6 flex flex-wrap items-baseline gap-3">
              <span className="font-display text-5xl font-bold text-foreground">
                {course.priceNew}
              </span>
              <span className="text-xl text-muted-foreground line-through">
                {course.priceOld}
              </span>
            </div>
            <p className="mt-2 text-sm text-primary">{course.discountNote}</p>

            <ul className="mt-7 flex flex-col gap-3">
              {perks.map((perk) => (
                <li
                  key={perk}
                  className="flex items-center gap-3 text-sm text-foreground"
                >
                  <span className="flex h-5 w-5 flex-none items-center justify-center rounded-full bg-primary/10 text-primary">
                    <Check className="h-3.5 w-3.5" />
                  </span>
                  {perk}
                </li>
              ))}
            </ul>

            <a
              href={`mailto:${course.email}?subject=Запись на курс «Основы рисования в Procreate»`}
              className="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-primary px-7 py-4 text-base font-medium text-primary-foreground transition-opacity hover:opacity-90"
            >
              Оплатить и получить доступ
              <ArrowRight className="h-4 w-4" />
            </a>
            <p className="mt-3 text-center text-xs text-muted-foreground">
              После оплаты доступ к урокам открывается в личном кабинете
            </p>
          </div>
        </div>
      </div>
    </section>
  )
}
