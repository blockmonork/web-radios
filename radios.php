<?php
header('Content-Type:text/html; charset=UTF-8');
?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Rádios</title>
	<style>
		body {
			background: black;
			color: white;
		}

		select {
			display: block;
			padding: 5px;
		}

		#bodi {
			margin: 5px auto;
			width: 50%;
		}

		#timer {
			border: 1px solid white;
			padding: 4px;
			margin: 2px;
			display: inline-block;
		}

		#timer a {
			border: 1px solid white;
			padding: 2px;
			display: block;
			margin: 2px;
			text-align: center;
		}
		#tocandoAgora, #tela, #telaD{
			display: inline-block;
			width: 100%;
			padding-top: 5px;
			margin-top: 5px;
		}
		#tocandoAgora::before{
			content: "Tocando agora: ";
		}

		a {
			cursor: pointer;
		}

		#footer {
			text-align: center;
			margin: 5px auto;
			width: 90%;
			size: .8em;
		}

		#playerContainer {
			margin: 10px;
		}
	</style>
</head>

<body>
	<div id="bodi">
		<h3>Rádios</h3>
		<select onchange="radioChange(this.value)">
			<option value="">[Selecione]</option>
			<option value="cafe">Café Brasil</option>
			<option value="98">98 FM</option>
			<option value="alv">Alvorada FM</option>
			<option value="ita1">Itatiaia 1</option>
			<option value="ita2">Itatiaia 2</option>
			<option value="antena1">Antena 1 FM</option>
			<option value="antena1_2">Antena 1 FM (vs.2)</option>
		</select>
		<span id="tocandoAgora"></span><br>
		<span id="tela">auto OFF</span> <br>
		<span id="telaD">auto ON</span> <br>
		<!-- TIMER -->
		<div id="timer">
			auto OFF <input id="tempo" placeholder="(min)" size="7" style="display:inline" type='number'>
			<hr>
			auto ON <input id="tempo_in" placeholder="hh:mm" size="17" style="display: inline;" type='time'>
			<hr>
			<a onclick="salva_tempo();">ok</a>
		</div>
		<div id="playerContainer"></div>
		<p><a style='color:white;' href="<?php echo $_SERVER['PHP_SELF']; ?>">reset</a></p>
	</div>

	<script>
		function g_e(id) {
			return document.getElementById(id) || false;
		}

		function write(id, txt) {
			g_e(id).innerHTML = txt;
		}

		function radioChange(n) {
			var _html = '';
			var agora = '';
			switch (n) {
				case 'cafe':
					agora = 'Rádio Café Brasil';
					_html = '<audio id="audioC" controls style="display:block;">';
					_html += '<source src="http://streamaudio.grupoumbrella.com.br:10181/;stream.mp3" type="audio/mp3">';
					_html += 'Your browser does not support the audio element.';
					_html += '</audio>';
					break;
				case 'itat':
					agora = 'Rádio Itatiaia';
					_html = '<audio id="audioC" controls style="display: block;">';
					_html += '<source src="http://icecast.mobradio.com.br:8000/web.aac" type="audio/aac" class="audio-mobile">';
					_html += '<source src="http://icecast.mobradio.com.br:8000/web.mp3" type="audio/mpeg" class="audio-mobile">';
					_html += 'Your browser does not support the audio element.';
					_html += '</audio>';
					break;
				case '98':
					agora = '98 FM';
					_html = '<audio id="audioC" controls style="display: block;">';
					_html += '<source src="http://stream.izap.com.br:443/98fm" type="audio/aac">';
					_html += 'Your browser does not support the audio element.';
					_html += '</audio>';
					break;
				case 'alv':
					agora = 'Alvorada FM';
					_html = '<audio id="audioC" controls style="display: block;">';
					_html += '<source src="http://streaming.mobradio.com.br:8002/live.aac">';
					_html += 'Your browser does not support the audio element.';
					_html += '</audio>';
					break;
				case 'ita1':
					agora = 'Itatiaia FM 1';
					_html = '<audio id="audioC" controls style="display: block;">';
					_html += '<source src="http://8903.brasilstream.com.br:8903/mp3">';
					_html += 'Your browser does not support the audio element.';
					_html += '</audio>';
					break;
				case 'ita2':
					agora = 'Itatiaia FM 2';
					_html = '<audio id="audioC" controls style="display: block;">';
					_html += '<source src="http://8903.brasilstream.com.br:8903/stream">';
					_html += 'Your browser does not support the audio element.';
					_html += '</audio>';
					break;
				case 'antena1':
					agora = 'Antena 1 FM';
					_html = '<audio id="audioC" controls style="display: block;">';
					_html += '<source src="http://antena1.newradio.it/stream2/fallback.m3u">';
					_html += 'Your browser does not support the audio element.';
					_html += '</audio>';
					break;
				case 'antena1_2':
					agora = 'Antena 1 FM (vs.2)'
					_html = '<audio id="audioC" autoplay controls style="display: block;">';
					_html += '<source src="https://stream.antena1.com.br/stream4" type="audio/mp4; codecs="mp4a.40.5" />';
					_html += '<source src="https://stream.antena1.com.br/stream4" type="audio/aacp" />';
					_html += '<source src="https://stream.antena1.com.br/stream5" type="audio/mpeg" />';
					_html += '</audio>';
					break;
			}
			if (_html != '') {
				write('tocandoAgora', agora);
				write('playerContainer', _html);
				g_e('audioC').play();
			}
		}
		var c = null;
		var s = -2;
		var hora_in = '';
		var min_in = '';
		var despertador = false;
		var despertou = false;
		var autoOFF = false;

		function salva_tempo() {
			if (c != null) {
				clearInterval(c);
				c = null;
			}
			s = -2;
			hora_in = min_in = '';
			despertador = despertou = autoOFF = false;
			var t = g_e('tempo').value;
			var tempo_in = g_e('tempo_in').value;
			if (t || tempo_in) {
				if (tempo_in) {
					despertador = true;
					despertou = false;
					let e = tempo_in.split(':');
					hora_in = e[0];
					min_in = e[1];
				}
				if (!isNaN(t)) {
					t = parseInt(t);
					s = t * 60;
					autoOFF = true;
				}
				if (despertador || autoOFF) {
					iniciar();
				}
			}
		}

		function iniciar() {
			c = setInterval('conta()', 1000);
		}

		function conta() {
			if (s > -2) {
				s--;
				if ((s < 0) && autoOFF) {
					auto_off();
				} else {
					write('tela', 'auto OFF ' + s + ' segs left');
				}
			}
			if (despertador) {
				auto_on();
			}
			check_status();
		}

		function auto_off() {
			write('tela', 'fim');
			g_e('audioC').pause();
			s = -2;
		}

		function auto_on() {
			if (!despertou) {
				var D = new Date();
				var H = D.getHours();
				var M = D.getMinutes();
				var S = D.getSeconds();
				write('telaD', 'autoON in ' + hora_in + ':' + min_in + ' agora ' + H + ':' + M + ':' + S);
				if (parseInt(hora_in) == H) {
					if (parseInt(min_in) == M) {
						g_e('audioC').play();
						despertou = true;
					}
				}
			}
		}

		function check_status() {
			if (autoOFF && despertador) {
				if ((autoOFF && s == -2) && (despertador && despertou)) {
					clearInterval(c);
				}
			} else if (!despertador && (autoOFF && s == -2)) {
				clearInterval(c);
			} else if (!autoOFF && (despertador && despertou)) {
				clearInterval(c);
			}
		}

		function limpa_tempo_in() {
			g_e('tempo_in').value = '00:00';
		}
	</script>
	<p id="footer">web-radios v.1 :: 1-f-a - 1 file application</p>
</body>

</html>