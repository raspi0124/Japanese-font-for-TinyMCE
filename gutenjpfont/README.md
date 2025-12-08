Blocks/styles are now built with `@wordpress/scripts`, a single entry point (`src/index.js`), and block metadata (`block.json`).

## Scripts

- `npm start` — dev build with watch.
- `npm run build` — production build into `build/` (not committed; Release CI should run this).
- `npm run lint:js` — lint JS with the default WordPress config.
- `npm run format` — format JS with Prettier.

## Notes

- Build artifacts live in `build/` and are registered via `register_block_type_from_metadata` at runtime.
- Legacy TinyMCE integration lives outside this package and remains untouched.
