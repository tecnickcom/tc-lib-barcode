Name:      %{_package}
Version:   %{_version}
Release:   %{_release}%{?dist}
Summary:   Provides tc-lib-barcode: PHP library to generate barcodes

Group:     Development/Libraries/PHP
License:   GNU-LGPL v3
URL:       https://github.com/tecnickcom/tc-lib-barcode

BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-%(%{__id_u} -n)
BuildArch: noarch

Requires:  php >= 5.3.3
Requires:  php-tc-lib-color >= 1.4.4

%description
Provides tc-lib-barcode: PHP classes to generate linear and bidimensional barcodes: CODE 39, ANSI MH10.8M-1983, USD-3, 3 of 9, CODE 93, USS-93, Standard 2 of 5, Interleaved 2 of 5, CODE 128 A/B/C, 2 and 5 Digits UPC-Based Extension, EAN 8, EAN 13, UPC-A, UPC-E, MSI, POSTNET, PLANET, RMS4CC (Royal Mail 4-state Customer Code), CBC (Customer Bar Code), KIX (Klant index - Customer index), Intelligent Mail Barcode, Onecode, USPS-B-3200, CODABAR, CODE 11, PHARMACODE, PHARMACODE TWO-TRACKS, Datamatrix ECC200, QR-Code, PDF417.

%build
(cd %{_current_directory} && make build)

%install
rm -rf $RPM_BUILD_ROOT
(cd %{_current_directory} && make install DESTDIR=$RPM_BUILD_ROOT)

%clean
rm -rf $RPM_BUILD_ROOT
(cd %{_current_directory} && make clean)

%files
%attr(-,root,root) %{_libpath}
%attr(-,root,root) %{_docpath}
%docdir %{_docpath}

%changelog

* Tue Feb 24 2015 Nicola Asuni <info@tecnick.com> 1.0.0-1
- Initial Commit
