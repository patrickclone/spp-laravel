<?php

function angkatanSekarang()
{
	[$tahun, $bulan] = explode('-', date('Y-n'));
	return $tahun - ($bulan >= 1 && $bulan <= 6 ? 1 : 0);
}