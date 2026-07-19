<?php
/**
 * Demo data seeder — creates sample news categories + posts so the
 * news portal homepage and category pages look populated while you practise.
 *
 * Usage (visit in the browser while logged in as admin):
 *   http://localhost/wordpress/?seed_demo=1       → create demo data
 *   http://localhost/wordpress/?seed_demo=clear   → remove demo data
 *
 * @package oceanwp-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', 'vk_demo_data_maybe_seed' );

function vk_demo_data_maybe_seed() {
	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! isset( $_GET['seed_demo'] ) ) {
		return;
	}
	$action = sanitize_key( wp_unslash( $_GET['seed_demo'] ) );

	if ( 'clear' === $action ) {
		vk_demo_data_clear();
		wp_safe_redirect( home_url( '/?demo_cleared=1' ) );
		exit;
	}

	vk_demo_data_seed();
	wp_safe_redirect( home_url( '/?demo_seeded=1' ) );
	exit;
}

/**
 * Show a small admin-style notice after seeding/clearing.
 */
add_action( 'wp_footer', 'vk_demo_data_notice' );
function vk_demo_data_notice() {
	if ( isset( $_GET['demo_seeded'] ) ) {
		echo '<div style="position:fixed;bottom:20px;left:20px;background:#6C2BD9;color:#fff;padding:14px 20px;border-radius:12px;font-family:Inter,sans-serif;box-shadow:0 10px 30px -10px rgba(108,43,217,.6);z-index:99999;">✅ Demo data created — your news portal is now populated.</div>';
	}
	if ( isset( $_GET['demo_cleared'] ) ) {
		echo '<div style="position:fixed;bottom:20px;left:20px;background:#0F0F1A;color:#fff;padding:14px 20px;border-radius:12px;font-family:Inter,sans-serif;z-index:99999;">🗑 Demo data removed.</div>';
	}
	if ( isset( $_GET['posts_enriched'] ) ) {
		echo '<div style="position:fixed;bottom:20px;left:20px;background:#6C2BD9;color:#fff;padding:14px 20px;border-radius:12px;font-family:Inter,sans-serif;box-shadow:0 10px 30px -10px rgba(108,43,217,.6);z-index:99999;">✅ Svi članci su obogaćeni dodatnim tekstom.</div>';
	}
}

/**
 * Create demo news categories + posts.
 */
function vk_demo_data_seed() {
	$categories = array(
		'novosti'   => 'Novosti',
		'misljenja' => 'Mišljenja',
		'sport'     => 'Sport',
		'kultura'   => 'Kultura',
		'zabava'    => 'Zabava',
	);

	$cat_ids = array();
	foreach ( $categories as $slug => $name ) {
		$existing = get_term_by( 'slug', $slug, 'category' );
		if ( $existing ) {
			$cat_ids[ $slug ] = $existing->term_id;
		} else {
			$res = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $res ) ) {
				$cat_ids[ $slug ] = $res['term_id'];
			}
		}
	}

	$posts = array(
		// Novosti
		array(
			'title'    => 'Vlada usvojila novi zakon o digitalnoj sigurnosti',
			'category' => 'novosti',
			'excerpt'  => 'Novi zakon donosi stroža pravila za kompanije koje rukuju ličnim podacima građana. Eksperti su podijeljeni oko efikasnosti.',
		),
		array(
			'title'    => 'Tehnološki gigant najavljuje pametne naočare za masovno tržište',
			'category' => 'novosti',
			'excerpt'  => 'Uređaj će, prema najavama, imati proširenu stvarnost i glasovnog asistenta u lokalnom jeziku.',
		),
		array(
			'title'    => 'Gradska uprava najavljuje rekonstrukciju centra',
			'category' => 'novosti',
			'excerpt'  => 'Planom je predviđeno više zelenih površina, biciklističkih staza i zona za pješake.',
		),
		array(
			'title'    => 'Meteorolozi upozoravaju na promjenu vremena narednih dana',
			'category' => 'novosti',
			'excerpt'  => 'Očekuju se kiša i jače buženje vjetra, građanima se preporučuje oprez u saobraćaju.',
		),
		array(
			'title'    => 'Novac za mlade naučnike u okviru nacionalnog programa',
			'category' => 'novosti',
			'excerpt'  => 'Stipendije će omogućiti istraživačkim timovima razvoj inovativnih projekata.',
		),
		array(
			'title'    => 'Aerodrom uvodi nove linije prema zapadnoj Evropi',
			'category' => 'novosti',
			'excerpt'  => 'Putnici će od naredne sezone imati više direktnih letova po pristupačnim cijenama.',
		),

		// Mišljenja
		array(
			'title'    => 'Mislilac dana: Da li je ubrzani život cijena modernog uspjeha?',
			'category' => 'misljenja',
			'excerpt'  => 'U novoj kolumni analiziramo kako kultura produktivnosti utiče na mentalno zdravlje mladih profesionalaca.',
		),
		array(
			'title'    => 'Kolumna: Zašto je važno slušati one s kojima se ne slažemo?',
			'category' => 'misljenja',
			'excerpt'  => 'Dijalog umjesto polarizacije može biti put ka boljem razumijevanju društvenih izazova.',
		),
		array(
			'title'    => 'Analiza: Kako društvene mreže oblikuju političke stavove',
			'category' => 'misljenja',
			'excerpt'  => 'Algoritmi sve više određuju šta vidimo, a time i kako razmišljamo o javnim pitanjima.',
		),
		array(
			'title'    => 'Gost kolumna: Budućnost obrazovanja je personalizovana',
			'category' => 'misljenja',
			'excerpt'  => 'Tehnologija omogućava prilagođavanje nastave svakom učeniku, ali je potrebna ravnoteža.',
		),
		array(
			'title'    => 'Da li je daljinski rad stvarno bolji za planetu?',
			'category' => 'misljenja',
			'excerpt'  => 'Manje vožnje znači manje emisija, ali veća potrošnja energije u domovima donosi dileme.',
		),
		array(
			'title'    => 'Komentar: O značaju lokalnih medija u malim zajednicama',
			'category' => 'misljenja',
			'excerpt'  => 'Kada veliki mediji ne pokrivaju regionalne teme, lokalni glas postaje ključan.',
		),

		// Sport
		array(
			'title'    => 'Košarkaška reprezentacija pobjedom otvorila kvalifikacije',
			'category' => 'sport',
			'excerpt'  => 'Srbija je u uvodnom kolu savladala rivala rezultatom 89:76. Ključni trenutak bila je odbrana u trećoj četvrtini.',
		),
		array(
			'title'    => 'Fudbalski derbi obilježio spektakl i kontroverzne sudijske odluke',
			'category' => 'sport',
			'excerpt'  => 'Navijači su ispratili uzbudljiv susret, dok stručnjaci komentarišu ključne trenutke s sudijskog aspekta.',
		),
		array(
			'title'    => 'Teniski turnir: Naša prvakinja u polufinalu',
			'category' => 'sport',
			'excerpt'  => 'Nakon uzbudljive meč-lopte, plasman među četiri najbolje izborila je i treća nositeljka turnira.',
		),
		array(
			'title'    => 'Odbojkašice sigurne u borbi za medalju',
			'category' => 'sport',
			'excerpt'  => 'Pobjedom u grupi osiguran je povoljan žrijeb u narednoj fazi takmičenja.',
		),
		array(
			'title'    => 'Mladi sprinter oborio državni rekord na 100 metara',
			'category' => 'sport',
			'excerpt'  => 'Rezultat 10,23 sekunde donosi velike nade za predstojeće evropsko prvenstvo.',
		),
		array(
			'title'    => 'Transferi: Klub iz lige potpisao iskusnog golmana',
			'category' => 'sport',
			'excerpt'  => 'Pojačanje iz inostranstva trebalo bi da donese stabilnost u odbrani novog tima.',
		),

		// Kultura
		array(
			'title'    => 'Nagrada za najbolji domaći film pripala mladom režiseru',
			'category' => 'kultura',
			'excerpt'  => 'Film koji je osvojio žiri ističe se autentičnom pričom i minimalističkom režijom.',
		),
		array(
			'title'    => 'Nova izložba u centru grada spaja tradicionalno i digitalno',
			'category' => 'kultura',
			'excerpt'  => 'Posjetioci mogu da upoznaju lokalne običaje kroz interaktivne instalacije i projekcije.',
		),
		array(
			'title'    => 'Koncertom u areni proslavljen rođendan legendarnog benda',
			'category' => 'kultura',
			'excerpt'  => 'Publici su se obradovali domaći i regionalni izvođači koji su interpretirali najveće hitove.',
		),
		array(
			'title'    => 'Književni klub: Zašto se vraćamo klasici?',
			'category' => 'kultura',
			'excerpt'  => 'Moderatori diskutuju o tome kako velika djela i dalje odgovaraju na aktuelna pitanja.',
		),
		array(
			'title'    => 'Pozorišna predstava za djecu osvojila nagradu publike',
			'category' => 'kultura',
			'excerpt'  => 'Interaktivna scenografija i duhoviti tekst učinili su predstavu omiljenom među najmlađima.',
		),
		array(
			'title'    => 'Fotografska monografija beleži život malog grada',
			'category' => 'kultura',
			'excerpt'  => 'Album autora iz lokalne zajednice prikazuje svakodnevicu kroz autentične kadrove.',
		),

		// Zabava
		array(
			'title'    => 'Fenomen proljetnih festivala: zašto svi žele na more ranije?',
			'category' => 'zabava',
			'excerpt'  => 'Rano rezervisanje karata i smještaja postalo je pravo natjecanje. Organizatori najavljuju rekordan broj izvođača.',
		),
		array(
			'title'    => '5 serija koje morate pogledati ovog mjeseca',
			'category' => 'zabava',
			'excerpt'  => 'Od trilera do dokumentaraca, naša lista pokriva različite žanrove za svaku večer.',
		),
		array(
			'title'    => 'Testirali smo: Najbolje kafice u gradu za rad na laptopu',
			'category' => 'zabava',
			'excerpt'  => 'WiFi, utičnice i atmosfera ključni su faktori koje smo uzeli u obzir pri rangiranju.',
		),
		array(
			'title'    => 'Vikend izleti: Pet destinacija na sat vremena od grada',
			'category' => 'zabava',
			'excerpt'  => 'Priroda, dobra hrana i mir idealni su recept za kratki bijeg iz svakodnevice.',
		),
		array(
			'title'    => 'Trikovi za savršenu kafu kod kuće',
			'category' => 'zabava',
			'excerpt'  => 'Od mlevenja zrna do temperature vode — sitnice koje čine veliku razliku u šolji.',
		),
		array(
			'title'    => 'Horoskop za ovu sedmicu: Šta donose planete?',
			'category' => 'zabava',
			'excerpt'  => 'Astrološki pregled za sve znakove, s fokusom na ljubav i karijeru.',
		),
		array(
			'title'    => 'Najbolji meme-ovi s prošlogodišnjeg festivala',
			'category' => 'zabava',
			'excerpt'  => 'Internetska zajednica brzo je reagovala na najzanimljivije trenutke sa scene.',
		),
	);

	foreach ( $posts as $i => $post ) {
		$title    = $post['title'];
		$cat_slug = $post['category'];
		$excerpt  = $post['excerpt'];

		$existing = get_page_by_title( $title, OBJECT, 'post' );
		if ( $existing ) {
			continue;
		}

		$content = vk_generate_post_content( $title, $cat_slug );

		$post_id = wp_insert_post( array(
			'post_title'   => $title,
			'post_content' => $content,
			'post_excerpt' => $excerpt,
			'post_status'  => 'publish',
			'post_type'    => 'post',
			'post_category' => isset( $cat_ids[ $cat_slug ] ) ? array( $cat_ids[ $cat_slug ] ) : array(),
			'post_date'    => date( 'Y-m-d H:i:s', strtotime( '-' . $i . ' days' ) ),
		) );

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_vk_demo', '1' );
		}
	}

	update_option( 'vk_demo_seeded', current_time( 'mysql' ) );
}

/**
 * Generate richer demo content for a post based on its category.
 *
 * @param string $title          Post title.
 * @param string $category_slug  Category slug.
 * @return string
 */
function vk_generate_post_content( $title, $category_slug ) {
	$title = esc_html( $title );

	$templates = array(
		'novosti'  => array(
			'<p>Vest pod naslovom <strong>%1$s</strong> izazvala je pažnju kako stručnjaka tako i šire javnosti. Događaj se odvija u trenutku kada društvo sve više prati aktuelna dešavanja i traži pouzdane informacije.</p><p>Prema dostupnim informacijama, ključni akteri već su izneli svoje stavove, dok se građani približno podeljeno izjašnjavaju o značaju ove teme. Stručnjaci naglašavaju da je važno sačekati sve zvanične detalje pre donošenja konačnih ocena.</p><h2>Kontekst i reakcije</h2><p>Ova vest stavlja u fokus pitanja koja su u poslednje vreme često tema javne rasprave. Komunikacija između institucija i građana ključna je za razumevanje svih aspekata.</p><p>Lokalni mediji prate situaciju u realnom vremenu i donosiće nove informacije čim budu potvrđene. Do tada se preporučuje oprez u deljenju neproverenih navoda.</p><h2>Zaključak</h2><p><strong>%1$s</strong> ostaje tema koju treba pratiti u narednim danima. Kao i uvek, preporučujemo čitanje više izvora kako biste formirali sopstveni stav.</p>',
			'<p><strong>%1$s</strong> je tema koja dominira današnjim informativnim prostorom. U pitanju je događaj koji ima šire društvene implikacije i zbog toga zaslužuje pažljivu analizu.</p><p>Zvaničnici su saopštili osnovne informacije, ali mnogi detalji još uvek nisu poznati. Građani su pozvani da prate samo proverene izvore i izbegavaju širenje dezinformacija.</p><h2>Šta dalje?</h2><p>Predstojeći sastanci i konferencije za novinare otkriće više o ovoj temi. Do tada, stručnjaci preporučuju miran pristup i objektivno sagledavanje činjenica.</p><p>Portal Viberi će i dalje pratiti ovu priču i ažurirati sadržaj kako nove informacije budu dostupne.</p><h2>Zaključak</h2><p><strong>%1$s</strong> je primer koliko je važno imati nezavisne medije koji pružaju kontekst i proveravaju informacije pre objavljivanja.</p>',
		),
		'misljenja' => array(
			'<p>Tema <strong>%1$s</strong> otvara prostor za razmišljanje o širem društvenom kontekstu. U ovoj kolumni analiziramo različite aspekte problema i pokušavamo da ponudimo uravnotežen pogled.</p><p>Jedan od ključnih argumenata jeste da je individualna odgovornost neodvojiva od sistema u kojem delujemo. S druge strane, kritičari upozoravaju da se ne sme zanemariti uticaj struktura i institucija.</p><h2>Za i protiv</h2><p>Argumenti koji podržavaju ovu perspektivu naglašavaju slobodu izbora, inovacije i lični napredak. Suprotno mišljenje ističe rizike polarizacije, nejednakosti i gubitka kolektivne solidarnosti.</p><p>Realnost je, kao i obično, negde između. Važno je slušati različite glasove, ali i zahtevati argumente zasnovane na činjenicama.</p><h2>Zaključak</h2><p><strong>%1$s</strong> nema jednostavan odgovor. Pozivamo vas da podelite svoje mišljenje u komentarima i učestvujete u konstruktivnoj diskusiji.</p>',
			'<p><strong>%1$s</strong> je pitanje koje sve češće postavljamo u razgovorima. U ovom tekstu pokušavamo da ga sagledamo iz više ugla, bez preuzimanja gotovih stavova.</p><p>Prva perspektiva naglašava lične slobode i mogućnost izbora. Druga ističe odgovornost prema zajednici i dugoročne posledice individualnih odluka.</p><h2>Kritički pogled</h2><p>Nije dovoljno reći da je nešto "dobro" ili "loše". Potrebno je razumeti ko profitira, ko gubi i koje su alternative. Tek tada možemo formirati stav koji nije produkt propagande.</p><p>Autor ove kolumne veruje da je dijalog ključan. Bez slušanja onih s kojima se ne slažemo, rizikujemo da živimo u informativnoj mehurići.</p><h2>Zaključak</h2><p><strong>%1$s</strong> ostaje otvoreno pitanje. Vaše mišljenje nam znači — ostavite komentar ispod teksta.</p>',
		),
		'sport'    => array(
			'<p>U fokusu teksta <strong>%1$s</strong> nalazi se sportski događaj koji je privukao pažnju navijača i stručnjaka. Susret je doneo mnogo uzbuđenja, a ključni trenuci odredili su konačan ishod.</p><p>Analiza utakmice pokazuje da je pobednička ekipa uspela da iskoristi slabosti protivnika, dok je poražena strana imala problema sa realizacijom prilika. Treneri su nakon meča istakli da je ekipa dala sve od sebe.</p><h2>Ključni momenti</h2><p>Prva polovina susreta protekla je u izjednačenoj borbi, dok su u nastavku promene u taktici donele prelom. Individualni učinak nekoliko igrača bio je presudan.</p><p>Statistika govori u prilog pobednika: više šuteva u okvir gola, bolja realizacija i čvršća odbrana u odlučujućim minutima.</p><h2>Zaključak</h2><p><strong>%1$s</strong> ostaje jedna od tema o kojoj će se još dugo pričati. Sledeće kolo donosi nove izazove, a mi ćemo biti tu da vas informišemo.</p>',
			'<p><strong>%1$s</strong> bio je u centru pažnje sportskih ljubitelja tokom proteklih dana. Očekivanja su bila velika, a susret je u mnogom opravdao hype.</p><p>Od samog početka videla se želja obe ekipe da kontrolišu igru. Međutim, mali detalji — greške u odbrani, preciznost u završnici i trenerske odluke — presudili su u konačnom rezultatu.</p><h2>Analiza igre</h2><p>Stručnjaci ističu da je pobednik odigrao disciplinovanije, posebno u odbrambenim zadacima. Ključni igrači su preuzeli odgovornost u odlučujućim trenucima.</p><p>Sa druge strane, porazena ekipa ima razloga za razmišljanje. Naredne nedelje donose nove prilike za ispravku utisaka.</p><h2>Zaključak</h2><p><strong>%1$s</strong> pokazuje zašto sport fascinira toliko ljudi. Pratite nas i dalje za kompletan pregled takmičenja.</p>',
		),
		'kultura'  => array(
			'<p>Događaj <strong>%1$s</strong> još jednom pokazuje koliko je kultura važan deo društvenog života. Publika je imala priliku da se susretne sa umetničkim delom koje ostavlja dubok utisak.</p><p>Kritičari su prepoznali autentičnost i hrabrost u pristupu, dok su posetioci naglasili emotivni doživljaj. Manje formalni komentari na društvenim mrežama pokazuju da je tema zaista podelila pažnju.</p><h2>Umetnički kontekst</h2><p>Ovo delo postavlja pitanja o identitetu, tradiciji i savremenom izrazu. Autor(i) jasno komuniciraju svoju viziju, ali ostavljaju prostora za ličnu interpretaciju.</p><p>Kultura, kao ogledalo društva, često prethodi širem društvenom razgovoru. Zato je važno da ovakvi događaji budu dostupni što široj publici.</p><h2>Zaključak</h2><p><strong>%1$s</strong> predstavlja vredan doprinos kulturnoj sceni. Preporučujemo da pratite najavu sledećih programa i izložbi.</p>',
			'<p><strong>%1$s</strong> privukao je pažnju ljubitelja umetnosti i kulture. Program je bio pažljivo osmišljen, a publika je nagradila trud autora i organizatora.</p><p>U tekstu koji sledi donosimo utiske sa događaja, razgovore sa učesnicima i kontekst u kojem je nastalo ovo delo. Cilj nam je da vam približimo ne samo ono što se desilo, već i zašto je to važno.</p><h2>Teme i poruke</h2><p>Centralna tema dela rezonira sa aktuelnim društvenim pitanjima. Bez obzira da li je reč o ljubavi, gubitku, identitetu ili borbi za pravdu, publika će prepoznati sopstvene dileme.</p><p>Osim umetničke vrednosti, događaj ima i edukativni potencijal. Mlađi posetioci imali su priliku da uče kroz doživljaj, a ne samo kroz teoriju.</p><h2>Zaključak</h2><p><strong>%1$s</strong> je poziv da kulturu doživimo kao nešto živo i neophodno. Nadamo se da ćete pronaći inspiraciju i vi.</p>',
		),
		'zabava'   => array(
			'<p>Tema <strong>%1$s</strong> donosi malo bezbrižnosti i zabave u svakodnevicu. U svetu punom obaveza, ovakvi sadržaji pomažu ljudima da se opuste i pronađu inspiraciju.</p><p>Naša redakcija istražila je različite aspekte ove teme i pripremila preporuke koje možete isprobati sami. Bilo da je reč o putovanju, seriji, receptu ili trendu, sigurno ćete pronaći nešto zanimljivo.</p><h2>Zanimljivosti i preporuke</h2><p>Stručnjaci za slobodno vreme savetuju da je balans između produktivnosti i odmora ključan za kvalitetan život. Zabava nije gubljenje vremena već važan deo regeneracije.</p><p>Ako vam se ovaj sadržaj dopao, podelite ga sa prijateljima i zapratite nas za više sličnih tekstova.</p><h2>Zaključak</h2><p><strong>%1$s</strong> je samo jedan od mnogih sadržaja koje možete pronaći na našem portalu. Uživajte u čitanju i vidimo se uskoro!</p>',
			'<p><strong>%1$s</strong> je savršen primer kako mala stvar može ulepšati dan. U ovom tekstu delimo utiske, savete i zanimljive detalje koji će vas možda inspirisati.</p><p>Bilo da planirate vikend, tražite novu seriju ili jednostavno želite da se opustite, ova tema donosi korisne ideje. Nismo želeli da budemo preozbiljni — zabava zaslužuje svoj prostor.</p><h2>Saveti za praktičnu primenu</h2><p>Isprobajte neke od preporuka koje smo pripremili. Često je najbolji način da ulepšate dan upravo jednostavan gest — dobra kafa, šetnja, omiljena pesma ili deo vremena proveden sa voljenima.</p><p>I ne zaboravite: zabava je subjektivna. Ono što nekom deluje otmjeno, drugome može biti dosadno. Važno je pronaći ono što vam prija.</p><h2>Zaključak</h2><p><strong>%1$s</strong> je naš poklon vama u prepunom rasporedu. Uživajte i recite nam šta biste voleli da pročitate sledeće.</p>',
		),
	);

	$category_templates = isset( $templates[ $category_slug ] ) ? $templates[ $category_slug ] : $templates['novosti'];
	$template           = $category_templates[ array_rand( $category_templates ) ];

	return sprintf( $template, $title );
}

/**
 * Enrich all existing posts with generated, category-related content.
 */
function vk_enrich_existing_posts() {
	$query = new WP_Query( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	) );

	foreach ( $query->posts as $post ) {
		$cats     = get_the_category( $post->ID );
		$cat_slug = ( ! empty( $cats ) && ! is_wp_error( $cats ) ) ? $cats[0]->slug : 'novosti';
		$content  = vk_generate_post_content( $post->post_title, $cat_slug );

		wp_update_post( array(
			'ID'           => $post->ID,
			'post_content' => $content,
		) );
	}
}

/**
 * Admin-only trigger to enrich existing posts.
 */
add_action( 'template_redirect', 'vk_enrich_posts_maybe_run' );
function vk_enrich_posts_maybe_run() {
	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! isset( $_GET['enrich_posts'] ) ) {
		return;
	}

	vk_enrich_existing_posts();
	wp_safe_redirect( home_url( '/?posts_enriched=1' ) );
	exit;
}

/**
 * Remove all demo posts.
 */
function vk_demo_data_clear() {
	$query = new WP_Query( array(
		'post_type'      => 'post',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'meta_key'       => '_vk_demo',
		'meta_value'     => '1',
		'fields'         => 'ids',
	) );

	foreach ( $query->posts as $pid ) {
		wp_delete_post( $pid, true );
	}

	delete_option( 'vk_demo_seeded' );
}
