<h1>Sikeres pályalemondás!</h1>

<p>{if is_null($admin_username) || $admin_username==""}Sikeresen lemondtad a foglalásod {else}Egy adminisztrátor lemondta a foglalásod {/if} a(z) {$reservation->timeunit->court->name} pályán {$reservation->timeunit->date} napon {$reservation->timeunit->from} - {$reservation->timeunit->to} időszakban.</p>

<p>Üdvözlettel: Kispest SE</p>