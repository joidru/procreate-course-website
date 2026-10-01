const works = [
  {
    src: '/artwork-landscape.png',
    alt: 'Атмосферный пейзаж в Procreate — перспектива и свет',
    caption: 'Пейзаж · перспектива',
  },
  {
    src: '/artwork-stilllife.png',
    alt: 'Натюрморт в Procreate — форма, объём и светотень',
    caption: 'Натюрморт · светотень',
  },
  {
    src: '/artwork-color.png',
    alt: 'Абстрактная работа в Procreate — теория цвета и гармония',
    caption: 'Этюд · цвет',
  },
  {
    src: '/artwork-figure.png',
    alt: 'Фигура человека в Procreate — построение и движение',
    caption: 'Фигура человека',
  },
]

export function Gallery() {
  return (
    <section id="works" className="bg-ink text-ink-foreground">
      <div className="mx-auto max-w-6xl px-5 py-16 md:px-8 md:py-24">
        <div className="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
          <div className="max-w-xl">
            <p className="text-sm font-medium text-primary">Работы курса</p>
            <h2 className="mt-2 text-balance font-display text-3xl font-bold tracking-tight md:text-4xl">
              Что вы научитесь рисовать
            </h2>
          </div>
          <p className="max-w-sm text-sm leading-relaxed text-ink-foreground/70">
            Примеры работ, которые ученики создают в процессе обучения — от
            простых этюдов до законченных иллюстраций.
          </p>
        </div>

        <div className="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4">
          {works.map((work) => (
            <figure
              key={work.src}
              className="group overflow-hidden rounded-2xl border border-white/10 bg-white/5"
            >
              <img
                src={work.src || '/placeholder.svg'}
                alt={work.alt}
                className="aspect-[3/4] w-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
              <figcaption className="px-3 py-3 text-xs text-ink-foreground/70">
                {work.caption}
              </figcaption>
            </figure>
          ))}
        </div>
      </div>
    </section>
  )
}
