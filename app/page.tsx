import { SiteHeader } from '@/components/site-header'
import { Hero } from '@/components/hero'
import { Stats } from '@/components/stats'
import { Program } from '@/components/program'
import { Gallery } from '@/components/gallery'
import { Included } from '@/components/included'
import { Pricing } from '@/components/pricing'
import { About } from '@/components/about'
import { Faq } from '@/components/faq'
import { SiteFooter } from '@/components/site-footer'

export default function Page() {
  return (
    <div className="min-h-screen bg-background">
      <SiteHeader />
      <main>
        <Hero />
        <Stats />
        <Program />
        <Gallery />
        <Included />
        <Pricing />
        <About />
        <Faq />
      </main>
      <SiteFooter />
    </div>
  )
}
