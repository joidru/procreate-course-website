import { course } from '@/lib/course'

export function SiteFooter() {
  return (
    <footer className="border-t border-border bg-background">
      <div className="mx-auto max-w-6xl px-5 py-12 md:px-8">
        <div className="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
          <div className="max-w-xs">
            <p className="font-display text-lg font-bold text-foreground">
              {course.author}
            </p>
            <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
              Онлайн-курс «{course.title}» — базовое рисование на iPad для
              начинающих.
            </p>
          </div>

          <div className="flex flex-col gap-2 text-sm">
            <p className="font-medium text-foreground">Контакты</p>
            <a
              href={`mailto:${course.email}`}
              className="text-muted-foreground transition-colors hover:text-foreground"
            >
              {course.email}
            </a>
          </div>

          <nav className="flex flex-col gap-2 text-sm">
            <p className="font-medium text-foreground">Документы</p>
            <a
              href="#"
              className="text-muted-foreground transition-colors hover:text-foreground"
            >
              Публичная оферта
            </a>
            <a
              href="#"
              className="text-muted-foreground transition-colors hover:text-foreground"
            >
              Политика конфиденциальности
            </a>
          </nav>
        </div>

        <div className="mt-10 flex flex-col gap-1 border-t border-border pt-6 text-xs text-muted-foreground md:flex-row md:items-center md:justify-between">
          <p>
            © {new Date().getFullYear()} {course.legalName}. {course.inn}
          </p>
          <p>Все права защищены.</p>
        </div>
      </div>
    </footer>
  )
}
