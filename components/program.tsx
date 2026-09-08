import {
  Boxes,
  Compass,
  Contrast,
  Palette,
  LayoutGrid,
  PersonStanding,
} from 'lucide-react'

const topics = [
  {
    icon: Boxes,
    title: 'Форма и объём',
    text: 'Строим объекты от простых геометрических форм к сложным, чувствуем массу и пространство.',
  },
  {
    icon: Compass,
    title: 'Перспектива',
    text: 'Линейная и воздушная перспектива, работа с горизонтом и точками схода.',
  },
  {
    icon: Contrast,
    title: 'Светотень',
    text: 'Свет, тень, полутень и рефлексы — как объём рождается из освещения.',
  },
  {
    icon: Palette,
    title: 'Теория цвета',
    text: 'Цветовой круг, гармонии и настроение, работа с палитрами в Procreate.',
  },
  {
    icon: LayoutGrid,
    title: 'Композиция',
    text: 'Основные правила построения кадра, ритм, баланс и акценты в работе.',
  },
  {
    icon: PersonStanding,
    title: 'Фигура человека',
    text: 'Базовые пропорции, построение и движение человеческой фигуры.',
  },
]

export function Program() {
  return (
    <section id="program" className="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
      <div className="max-w-2xl">
        <p className="text-sm font-medium text-primary">Программа</p>
        <h2 className="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">
          Все базовые правила рисования за 30 уроков
        </h2>
        <p className="mt-4 text-pretty text-base leading-relaxed text-muted-foreground">
          Каждый блок — это отдельный навык. Мы идём последовательно: от простого
          к сложному, чтобы к концу курса вы могли собрать полноценную работу.
        </p>
      </div>

      <div className="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {topics.map((topic) => (
          <div
            key={topic.title}
            className="group rounded-2xl border border-border bg-card p-6 transition-colors hover:border-primary/40"
          >
            <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-accent text-primary">
              <topic.icon className="h-5 w-5" />
            </div>
            <h3 className="mt-4 font-display text-lg font-semibold text-foreground">
              {topic.title}
            </h3>
            <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
              {topic.text}
            </p>
          </div>
        ))}
      </div>
    </section>
  )
}
