import { mkdir, cp } from 'node:fs/promises';
await mkdir('dist', { recursive: true });
for (const file of ['index.html', 'basics.html', 'checkout.html', 'payment-success.html', 'payment-failed.html', 'styles.css', 'script.js', 'assets', 'public']) {
  await cp(file, `dist/${file}`, { recursive: true });
}
console.log('Static site copied to dist. No runtime dependencies required.');
