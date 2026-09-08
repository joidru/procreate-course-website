import { course } from '@/lib/course'

export function Stats() {
  return (
    <section className="mx-auto max-w-6xl px-5 md:px-8">
      <dl className="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-border bg-border md:grid-cols-4">
        {course.stats.map((stat) => (
          <div key={stat.label} className="bg-card px-5 py-6 text-center">
            <dt className="text-xs uppercase tracking-wide text-muted-foreground">
              {stat.label}
            </dt>
            <dd className="mt-2 font-display text-xl font-bold text-foreground md:text-2xl">
              {stat.value}
            </dd>
          </div>
        ))}
      </dl>
    </section>
  )
}
