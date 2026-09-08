import { Check } from 'lucide-react'

const items = [
  'Все базовые правила рисования в Procreate',
  'Построение формы и объектов',
  'Теория цвета и гармония',
  'Основы композиции',
  'Перспектива и светотень',
  'Фигура человека',
  '30 уроков в личном кабинете',
  'Авторский набор кистей Procreate',
  'Готовые исходники со слоями в Procreate',
  '20 текстур холстов большого размера',
  '45 референсов для практики',
  'Чат учеников и обратная связь',
]

export function Included() {
  return (
    <section
      id="included"
      className="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24"
    >
      <div className="grid gap-10 md:grid-cols-[1fr_1.3fr] md:gap-16">
        <div>
          <p className="text-sm font-medium text-primary">Что входит</p>
          <h2 className="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">
            Всё для практики — в одном курсе
          </h2>
          <p className="mt-4 text-pretty text-base leading-relaxed text-muted-foreground">
            Вы получаете не только уроки, но и рабочие материалы: кисти,
            исходники, текстуры и референсы. Всё, чтобы сразу начать рисовать.
          </p>
        </div>

        <ul className="grid gap-x-6 gap-y-1 sm:grid-cols-2">
          {items.map((item) => (
            <li
              key={item}
              className="flex items-start gap-3 border-b border-border/70 py-3 text-sm text-foreground"
            >
              <span className="mt-0.5 flex h-5 w-5 flex-none items-center justify-center rounded-full bg-primary/10 text-primary">
                <Check className="h-3.5 w-3.5" />
              </span>
              {item}
            </li>
          ))}
        </ul>
      </div>
    </section>
  )
}
