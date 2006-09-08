#!/usr/bin/perl -CSD
#

use strict;
use warnings;
use utf8;


# OPTIONS
use vars qw($opt_d);
use Getopt::Std;
# I don't know how this works - but it works:
getopts('a:d:f:hlnt:u:v');




# defaults:
$opt_d ||= 'lang_guess/';

#print $opt_d;

require $opt_d."Guess.pm";
# use Language::Guess;

my $guesser = Language::Guess->new( modeldir => $opt_d."train/" );


while (my $line = <> ) {
	my $lang = $guesser->simple_guess($line);
	print "$lang\n\n";
}