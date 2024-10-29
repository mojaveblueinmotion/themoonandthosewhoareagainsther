FROM mcr.microsoft.com/mssql/server:2017-latest-ubuntu

ENV MSSQL_PID=Express
ENV MSSQL_DATABASE=$DB_DATABASE
ENV ACCEPT_EULA=Y
ENV SA_PASSWORD="yourStrong(!)Password"
ENV MSSQL_SA_PASSWORD="yourStrong(!)Password"

VOLUME /var/opt/mssql

EXPOSE 1433
