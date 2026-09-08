import { course } from '@/lib/course'

export function About() {
  return (
    <section id="author" className="bg-accent/40">
      <div className="mx-auto grid max-w-6xl items-center gap-10 px-5 py-16 md:grid-cols-2 md:gap-16 md:px-8 md:py-24">
        <div className="order-2 md:order-1">
          <p className="text-sm font-medium text-primary">Автор курса</p>
          <h2 className="mt-2 text-balance font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">
            {course.author}
          </h2>
          <div className="mt-5 flex flex-col gap-4 text-pretty text-base leading-relaxed text-muted-foreground">
            <p>
              Художница и преподаватель цифрового рисунка. Много лет рисую в
              Procreate и помогаю ученикам пройти путь от первых линий до
              законченных иллюстраций.
            </p>
            <p>
              В этом курсе я собрала базу, которой мне самой не хватало в начале:
              понятную последовательность, реальные примеры и рабочие материалы.
              Никакой воды — только то, что действительно нужно, чтобы рисовать.
            </p>
          </div>
          <a
            href="#pricing"
            className="mt-7 inline-flex items-center justify-center rounded-full bg-foreground px-7 py-3.5 text-base font-medium text-background transition-opacity hover:opacity-90"
          >
            Пройти курс с Евгенией
          </a>
        </div>

        <div className="order-1 md:order-2">
          <div className="overflow-hidden rounded-[1.75rem] border border-border bg-card p-2 shadow-xl shadow-foreground/5">
            <img
              src="/artwork-workspace.png"
              alt="Рабочее место художницы — iPad с Procreate и рисунок"
              className="aspect-[4/5] w-full rounded-[1.25rem] object-cover"
            />
          </div>
        </div>
      </div>
    </section>
  )
}
