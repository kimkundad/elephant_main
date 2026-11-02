# Elephant → program link, and an Our Elephants menu entry

## Why

A guest who falls for an elephant on the home page should land on the program
where they can meet it, not on a general elephants page. The client wants each
elephant's "Read more" button to open that elephant's program, e.g.
`/v2/tours/short-visit-feeding-shower-kcp`, and wants the elephants page reachable
from the menus.

## What changes

1. **Admin → Elephants**: the create and edit forms get a required "Program"
   dropdown listing the tours. One program per elephant.
2. **Home page, "Meet Our Elephants"**: "Read more" goes to the chosen program's
   page. The link is built from the tour's slug, never typed by the admin.
3. **Fallback**: an elephant with no program yet (every existing one, until an
   admin edits it), or whose program is inactive or deleted, keeps today's link:
   the Our Elephants page anchored at that elephant.
4. **Menus**: an "Our Elephants" / "ช้างของเรา" entry, placed after Programs, in
   - the header pill nav (desktop),
   - the full-screen hamburger menu, using the shared hover image until the
     client uploads one of its own,
   - the footer's Quick link list.

## Approaches considered

- **A. Pick a program from a dropdown (chosen).** Stores the tour id on the
  elephant. Links can't be mistyped, survive a slug change, and we can tell when
  the program is switched off and fall back.
- **B. Free-text URL field.** Links anywhere, but can be mistyped, silently
  breaks on a slug change or a disabled tour, and can't enforce "must be a
  program", which is what the client asked for.

The client picked A, and the fallback in point 3.

## Constraints

- Existing elephants must keep working without an admin touching them, so the
  column is nullable in the database; "required" is enforced by form validation
  only.
- Deleting a tour must not delete or break an elephant: the foreign key nulls out
  on delete.

## Non-goals

- Several programs per elephant.
- Changing the Our Elephants page itself.

## Left to the builder

- Which tours the dropdown lists (all, or active only) and how they're labelled.
- Which tour route the link uses: match whatever the program cards use, so the
  link looks the same as the others on the site.
- The menu's language-file keys, and whether the new hover image is exposed in
  Page Media the way the other menu images are.
