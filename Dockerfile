# build the Schematron XSL files from the Schematron source files
FROM hubdock/php7-apache-saxonhe:12.1 AS builder

WORKDIR /build
COPY build ./

ARG JATS4R_SCHEMATRONS_VERSION=0.0.10
RUN curl -L https://github.com/JATS4R/jats-schematrons/archive/v${JATS4R_SCHEMATRONS_VERSION}.tar.gz | tar xvz
RUN php generate-xsl.php jats-schematrons-${JATS4R_SCHEMATRONS_VERSION}/schematrons/1.0/jats4r.sch jats4r.xsl
RUN cp jats-schematrons-${JATS4R_SCHEMATRONS_VERSION}/schematrons/1.0/*.xml .

ARG SCHEMATRONS_COMMIT=30331a1b7d1a641457b57d778e1e79e36d9822b3
RUN curl -L https://github.com/elifesciences/eLife-JATS-schematron/raw/${SCHEMATRONS_COMMIT}/src/pre-JATS-schematron.sch -o elife-schematron-pre.sch
RUN php generate-xsl.php elife-schematron-pre.sch elife-pre.xsl

RUN curl -L https://github.com/elifesciences/eLife-JATS-schematron/raw/${SCHEMATRONS_COMMIT}/src/final-JATS-schematron.sch -o elife-schematron-final.sch
RUN php generate-xsl.php elife-schematron-final.sch elife-final.xsl

# fetch the DTDs and copy the Schematron XSL files into place
FROM hubdock/php7-apache-saxonhe:12.1

WORKDIR /dtds
ARG DTDS_VERSION=0.0.8
ENV DTDS_VERSION=${DTDS_VERSION}
RUN curl -L https://github.com/JATS4R/jats-dtds/archive/v${DTDS_VERSION}.tar.gz | tar xvz
ENV XML_CATALOG_FILES=/dtds/jats-dtds-${DTDS_VERSION}/schema/catalog.xml

WORKDIR /var/www/html
ARG VALIDATOR_COMMIT=8709245e77739204d62f6ca3745a411c69c5ec0a
# RUN curl https://raw.githubusercontent.com/elifesciences/schematron-validator/${VALIDATOR_COMMIT}/backend/schematron-validator-api/countries.xml -o countries.xml
RUN curl https://raw.githubusercontent.com/elifesciences/schematron-validator/${VALIDATOR_COMMIT}/backend/schematron-validator-api/journal-DOI.xml -o journal-DOI.xml
RUN curl https://raw.githubusercontent.com/elifesciences/schematron-validator/${VALIDATOR_COMMIT}/backend/schematron-validator-api/publisher-locations.xml -o publisher-locations.xml

COPY cli/ ./
COPY web/ ./
COPY functions/ ../functions/
COPY --from=builder /build/*.xsl ./
COPY --from=builder /build/*.xml ./
RUN ls -al
