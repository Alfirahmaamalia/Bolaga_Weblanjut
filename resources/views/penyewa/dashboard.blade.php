<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Beranda - Cari Lapangan Olahraga</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F7F7F5] text-[#1b1b18] min-h-screen">
	<!-- Top Bar -->
	<header class="sticky top-0 z-30 w-full border-b border-[#e3e3e0] bg-white/90 backdrop-blur">
		<div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
			<a href="/" class="text-xl font-semibold tracking-tight">Bolaga</a>
			<nav class="hidden md:flex items-center gap-6 text-sm">
				<a href="{{ route('penyewa.dashboard') }}" class="text-[#1b1b18] hover:underline underline-offset-4">Beranda</a>
				<a href="#" class="text-[#706f6c] hover:text-[#1b1b18]">Kategori</a>
				<a href="#" class="text-[#706f6c] hover:text-[#1b1b18]">Riwayat</a>
				@auth
					<div class="flex items-center gap-4">
						<span>Halo, {{ Auth::user()->nama }}</span>
						<form action="{{ route('logout') }}" method="POST">
							@csrf
							<button type="submit" class="px-4 py-2 border rounded-full hover:bg-black hover:text-white transition">Logout</button>
						</form>
					</div>
				@else
					<a href="{{ route('login') }}" class="px-4 py-2 border rounded-full hover:bg-black hover:text-white transition">Masuk</a>
				@endauth
			</nav>
		</div>
	</header>

	<main class="max-w-7xl mx-auto px-4 py-8">
		<!-- Heading -->
		<section class="text-center mb-8">
			<h1 class="text-2xl md:text-3xl font-extrabold tracking-wide">Cari Lapangan Olahraga</h1>
			<p class="text-[#706f6c] mt-1">Temukan lapangan sesuai kebutuhan Anda</p>
		</section>

		<!-- Filter Bar -->
		<form class="bg-white rounded-xl border border-[#e3e3e0] p-4 md:p-5 mb-8 shadow-[0_1px_2px_rgba(0,0,0,.04)]">
			<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
				<div>
					<label class="block text-xs text-[#706f6c] mb-1">Jenis Olahraga</label>
					<select class="w-full border border-[#e3e3e0] rounded-lg px-3 py-2 focus:ring-2 focus:ring-black/10 outline-none">
						<option>Semua</option>
						<option>Futsal</option>
						<option>Badminton</option>
						<option>Basket</option>
						<option>Voli</option>
						<option>Tenis</option>
					</select>
				</div>
				<div>
					<label class="block text-xs text-[#706f6c] mb-1">Lokasi</label>
					<select class="w-full border border-[#e3e3e0] rounded-lg px-3 py-2 focus:ring-2 focus:ring-black/10 outline-none">
						<option>Semua Lokasi</option>
						<option>Jakarta</option>
						<option>Bandung</option>
						<option>Surabaya</option>
						<option>Yogyakarta</option>
					</select>
				</div>
				<div>
					<label class="block text-xs text-[#706f6c] mb-1">Rentang Harga</label>
					<select class="w-full border border-[#e3e3e0] rounded-lg px-3 py-2 focus:ring-2 focus:ring-black/10 outline-none">
						<option>Semua Harga</option>
						<option><= Rp100.000</option>
						<option>Rp100.000 - Rp250.000</option>
						<option>>= Rp250.000</option>
					</select>
				</div>
				<div class="flex gap-2 self-end">
					<div class="relative flex-1">
						<input type="text" placeholder="Cari lapangan..." class="w-full border border-[#e3e3e0] rounded-lg pl-9 pr-3 py-2 focus:ring-2 focus:ring-black/10 outline-none" />
						<svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#706f6c]" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
					</div>
					<button type="submit" class="px-4 py-2 rounded-lg bg-black text-white hover:opacity-90 transition">Cari</button>
				</div>
			</div>

			<!-- Quick categories -->
			<div class="mt-4 flex flex-wrap gap-2">
				@foreach (['Semua','Futsal','Badminton','Basket','Voli','Tenis'] as $cat)
					<button type="button" class="px-3 py-1.5 rounded-full border border-[#e3e3e0] text-sm hover:border-black/60">{{ $cat }}</button>
				@endforeach
			</div>
		</form>

		@php
			// Konfigurasi gambar lokal. Taruh file-file berikut di public/images/:
			// futsal.jpg, badminton.jpg, basket.jpg, voli.jpg, tenis.jpg
			$img = function (string $filename, string $fallback) {
				$path = public_path('images/'.$filename);
				return file_exists($path) ? asset('images/'.$filename) : $fallback;
			};

			$cards = [
				[
					'jenis' => 'Futsal',
					'nama' => 'Arena Futsal Nusantara',
					'harga' => 150000,
					'lokasi' => 'Jakarta Selatan',
					'fasilitas' => ['AC', 'Parkir', 'Toilet', 'Kantin'],
					'gambar' => $img('futsal.jpg', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200&q=80&auto=format&fit=crop')
				],
				[
					'jenis' => 'Badminton',
					'nama' => 'Satria Nugraha Badminton',
					'harga' => 250000,
					'lokasi' => 'Tanjung Karang Pusat',
					'fasilitas' => ['AC', 'Raket Pinjaman', 'Toilet', 'Kantin'],
					'gambar' => $img('badminton.jpg', asset('images/lapangan.jpg'))
				],
				[
					'jenis' => 'Basket',
					'nama' => 'Satria Nugraha Basket',
					'harga' => 250000,
					'lokasi' => 'Tanjung Karang Pusat',
					'fasilitas' => ['AC', 'Parkir', 'Toilet', 'Kantin'],
					'gambar' => $img('basket.jpg', 'https://images.unsplash.com/photo-1531312267124-2f7f7486f7b0?w=1200&q=80&auto=format&fit=crop')
				],
				[
					'jenis' => 'Voli',
					'nama' => 'Samudra Volley Court',
					'harga' => 200000,
					'lokasi' => 'Bandung',
					'fasilitas' => ['Parkir', 'Toilet', 'Kantin'],
					'gambar' => $img('voli.jpg', 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1200&q=80&auto=format&fit=crop')
				],
				[
					'jenis' => 'Tenis',
					'nama' => 'Green Park Tennis',
					'harga' => 300000,
					'lokasi' => 'Surabaya',
					'fasilitas' => ['AC', 'Parkir', 'Toilet', 'Kantin'],
					'gambar' => $img('tenis.jpg', 'https://images.unsplash.com/photo-1605649487212-47bdab064df3?w=1200&q=80&auto=format&fit=crop')
				],
			];
			$items = array_merge($cards, $cards); // gandakan secukupnya
		@endphp

		<!-- Grid Cards -->
		<section class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
			@foreach($items as $item)
				<article class="group bg-white border border-[#e3e3e0] rounded-xl overflow-hidden shadow-[0_1px_2px_rgba(0,0,0,.04)] hover:shadow-[0_6px_20px_rgba(0,0,0,.06)] transition">
					<div class="aspect-[4/3] w-full overflow-hidden bg-[#f3f3f2]">
						<img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition" onerror="this.onerror=null;this.src='https://picsum.photos/seed/court/600/400';">
					</div>
					<div class="p-4">
						<div class="flex items-center justify-between text-xs mb-2">
							<span class="px-2 py-0.5 rounded-full bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0]"> {{ $item['jenis'] }} </span>
							<div class="flex items-center gap-1 text-[#706f6c]">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 .587l3.668 7.568L24 9.75l-6 5.853L19.335 24 12 19.771 4.665 24 6 15.603 0 9.75l8.332-1.595z"/></svg>
								<span>4.7</span>
							</div>
						</div>
						<h3 class="font-semibold leading-snug line-clamp-2">{{ $item['nama'] }}</h3>
						<div class="mt-1 text-xs text-[#706f6c] flex items-center gap-1">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/></svg>
							<span>{{ $item['lokasi'] }}</span>
						</div>
						@php
							$fasilitas = is_array($item) ? ($item['fasilitas'] ?? null) : ($item->fasilitas ?? null);
						@endphp
						@if($fasilitas && !empty($fasilitas))
							<div class="mt-2">
								<p class="text-xs text-[#706f6c] mb-1.5">Fasilitas:</p>
								<div class="flex flex-wrap gap-1.5">
									@foreach($fasilitas as $fas)
										<span class="px-2 py-0.5 rounded-full bg-[#ECFDF5] text-[#047857] border border-[#A7F3D0] text-xs">{{ $fas }}</span>
									@endforeach
								</div>
							</div>
						@endif
						<div class="mt-3 flex items-center justify-between">
							<div class="text-sm"><span class="font-semibold">Rp{{ number_format($item['harga'],0,',','.') }}</span><span class="text-xs text-[#706f6c]">/jam</span></div>
							<a href="#" class="px-4 py-2 text-white bg-[#16a34a] rounded-full text-xs hover:bg-[#128a3e]">Booking</a>
						</div>
					</div>
				</article>
			@endforeach
		</section>
	</main>
</body>
</html>


