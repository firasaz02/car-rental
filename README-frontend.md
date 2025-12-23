# Frontend migration: Angular -> Laravel (Vite)

This project originally included an Angular frontend. The repository now contains a lightweight Laravel + Vite frontend that reproduces the essential functionality (maps, realtime updates, vehicle management). This document maps Angular concepts (from `src/app/app.module.ts`) to the Laravel implementation and explains how to run and extend the frontend.

## Angular app.module.ts mapping

Angular AppModule:
- BrowserModule -> Provided by the browser when serving Laravel views. No explicit import needed.
- AppRoutingModule -> Laravel handles routes via `routes/web.php` (see `/` `/map` `/vehicles`).
- HttpClientModule -> Replaced by `axios` configured in `resources/js/bootstrap.js`.
- ReactiveFormsModule -> Replaced by vanilla JS form handling in `resources/js/components/vehicleManagement.js`; you can convert this to Vue or React if you need richer forms.
- GoogleMapsModule -> Replaced by `@googlemaps/js-api-loader` used in `resources/js/app.js` and `resources/js/components/mapComponent.js`.

Replaced Angular components and their Laravel equivalents:
- `MapComponent` -> `resources/js/components/mapComponent.js` + `resources/views/map.blade.php`
- `VehicleManagementComponent` -> `resources/js/components/vehicleManagement.js` + `resources/views/vehicle-management.blade.php`

Services:
- `VehicleService` (Angular) -> `resources/js/services/vehicleService.js` (axios wrappers calling `/api/v1` endpoints)
- Real-time (Pusher/Echo) -> `resources/js/services/pusherService.js` and `resources/js/bootstrap.js` (Echo + Pusher setup)

Blade views:
- `resources/views/index.blade.php` - root page
- `resources/views/map.blade.php` - map page
- `resources/views/vehicle-management.blade.php` - vehicle CRUD UI

Vite entry:
- `resources/js/app.js` is the main frontend entry. It loads the Google Maps API and the JS modules.

## Environment variables
Add the following to your `.env` (some already present):

- VITE_API_URL=/api/v1 (or your API base URL)
- VITE_GOOGLE_MAPS_KEY=your_google_maps_key
- VITE_PUSHER_APP_KEY=your_pusher_key (if using Pusher)
- VITE_PUSHER_APP_CLUSTER=your_pusher_cluster

Server-side Pusher settings if you broadcast events:
- PUSHER_APP_ID
- PUSHER_APP_KEY
- PUSHER_APP_SECRET
- PUSHER_APP_CLUSTER
- BROADCAST_DRIVER=pusher

## Run locally
1. Install frontend deps and start Vite (run in PowerShell):

```powershell
npm install
npm run dev
```

2. Serve Laravel:

```powershell
php artisan serve
```

### npm scripts / Angular command equivalents

If you're used to Angular commands, here are the Laravel/Vite equivalents provided in `package.json`:

- `ng serve` -> `npm run serve` (starts Vite and `php artisan serve` in parallel via `concurrently`)
- `npm install` -> `npm install` (same)
- `ng build --prod` -> `npm run build:prod` (runs `vite build` then `php artisan optimize`)

You can also use `npm run dev` or `npm start` to run Vite in dev mode.

3. Visit pages:
- /map
- /vehicles

## How the pieces interact
- The map component fetches `/api/v1/locations/current` to get current vehicle positions and renders markers.
- When a new `VehicleLocation` is stored via the API, the backend broadcasts a `VehicleLocationUpdated` event. The frontend subscribes using Echo and updates markers in real-time.
- The vehicle management page uses `vehicleService` to CRUD vehicles and updates the list.

## Next steps / recommendations
- Move vehicle management UI into a small framework-backed component (Vue/React) for better maintainability.
- Add client-side validation and nicer toasts instead of alerts.
- If you expect scale, consider paginating vehicles and limiting realtime subscriptions.

If you want, I can:
- Convert the management UI to Vue and wire it into Vite.
- Replace alerts with a toast system.
- Add unit tests for the JS modules.

---

If you'd like a different format (in-repo docs, confluence-ready markdown, or a README in another language), tell me which and I'll produce it.