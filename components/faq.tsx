'use client'

import { useState } from 'react'
import { Plus } from 'lucide-react'

const faqs = [
  {
    q: 'Нужен ли опыт рисования?',
    a: 'Нет. Курс построен с нуля — мы начинаем с самых основ, поэтому подойдёт даже тем, кто впервые взял в руки Apple Pencil.',
  },
  {
    q: 'Что нужно для обучения?',
    a: 'iPad с приложением Procreate и стилус (Apple Pencil или совместимый). Все кисти, текстуры и исходники входят в курс.',
  },
  {
    q: 'Как долго доступны уроки?',
    a: 'Доступ к курсу остаётся у вас навсегда. Вы можете проходить уроки в удобном темпе и возвращаться к ним в любой момент.',
  },
  {
    q: 'Будет ли обратная связь?',
    a: 'Да. Есть чат учеников и бесплатные консультации — вы сможете задавать вопросы и получать разбор своих работ.',
  },
  {
    q: 'Как проходит оплата и доступ?',
    a: 'После оплаты уроки открываются в личном кабинете. По вопросам оплаты напишите нам на почту — поможем оформить доступ.',
  },
]

export function Faq() {
  const [openIndex, setOpenIndex] = useState<number | null>(0)

  return (
    <section className="mx-auto max-w-3xl px-5 py-16 md:px-8 md:py-24">
      <h2 className="text-balance text-center font-display text-3xl font-bold tracking-tight text-foreground md:text-4xl">
        Частые вопросы
      </h2>

      <div className="mt-10 flex flex-col gap-3">
        {faqs.map((faq, index) => {
          const isOpen = openIndex === index
          return (
            <div
              key={faq.q}
              className="overflow-hidden rounded-2xl border border-border bg-card"
            >
              <button
                type="button"
                onClick={() => setOpenIndex(isOpen ? null : index)}
                aria-expanded={isOpen}
                className="flex w-full items-center justify-between gap-4 px-5 py-4 text-left md:px-6 md:py-5"
              >
                <span className="font-display text-base font-semibold text-foreground md:text-lg">
                  {faq.q}
                </span>
                <Plus
                  className={`h-5 w-5 flex-none text-primary transition-transform duration-200 ${
                    isOpen ? 'rotate-45' : ''
                  }`}
                />
              </button>
              {isOpen && (
                <p className="px-5 pb-5 text-sm leading-relaxed text-muted-foreground md:px-6">
                  {faq.a}
                </p>
              )}
            </div>
          )
        })}
      </div>
    </section>
  )
}
