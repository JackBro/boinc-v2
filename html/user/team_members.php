<?php
// This file is part of BOINC.
// http://boinc.berkeley.edu
// Copyright (C) 2008 University of California
//
// BOINC is free software; you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License
// as published by the Free Software Foundation,
// either version 3 of the License, or (at your option) any later version.
//
// BOINC is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public License
// along with BOINC.  If not, see <http://www.gnu.org/licenses/>.

require_once("../inc/util.inc");
require_once("../inc/team.inc");
require_once("../inc/cache.inc");

check_get_args(array("sort_by", "offset", "teamid"));

if (isset($_GET["sort_by"])) {
    $sort_by = $_GET["sort_by"];
    $sort_by = strip_tags($sort_by);    // remove XSS nonsense
} else {
    $sort_by = "expavg_credit";
}

$offset = get_int("offset", true);
if (!$offset) $offset=0;

if ($offset > 1000) {
    error_page(tra("Limit exceeded:  Can only display the first 1000 members."));
}

$teamid = get_int("teamid");

$cache_args = "teamid=$teamid";
$team = unserialize(get_cached_data(TEAM_PAGE_TTL, $cache_args));
if (!$team) {
    $team = BoincTeam::lookup_id($teamid);
    if (!$team) error_page("no such team");
    set_cached_data(TEAM_PAGE_TTL, serialize($team), $cache_args);
}

page_head(tra("Members of %1", "<a href=team_display.php?teamid=$teamid>$team->name</a>"));
display_team_members($team, $offset, $sort_by);
page_tail();

?>
