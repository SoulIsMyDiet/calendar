--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: event_id_seq; Type: SEQUENCE; Schema: public; Owner: ziom
--

CREATE SEQUENCE event_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.event_id_seq OWNER TO ziom;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: events; Type: TABLE; Schema: public; Owner: ziom; Tablespace: 
--

CREATE TABLE events (
    event_id integer DEFAULT nextval('event_id_seq'::regclass) NOT NULL,
    event_title character varying(80) DEFAULT NULL::character varying,
    event_desc text,
    event_start timestamp without time zone NOT NULL,
    event_end timestamp without time zone NOT NULL
);


ALTER TABLE public.events OWNER TO ziom;

--
-- Name: user_cal_serial; Type: SEQUENCE; Schema: public; Owner: ziom
--

CREATE SEQUENCE user_cal_serial
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_cal_serial OWNER TO ziom;

--
-- Name: users; Type: TABLE; Schema: public; Owner: ziom; Tablespace: 
--

CREATE TABLE users (
    user_id integer DEFAULT nextval('user_cal_serial'::regclass),
    user_name character varying NOT NULL,
    user_pass character varying NOT NULL,
    user_email character varying NOT NULL
);


ALTER TABLE public.users OWNER TO ziom;

--
-- Name: event_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ziom
--

SELECT pg_catalog.setval('event_id_seq', 5, true);


--
-- Data for Name: events; Type: TABLE DATA; Schema: public; Owner: ziom
--

INSERT INTO events VALUES (1, 'uro Sylwii', 'lepiej o nich nie zapomniec', '2017-04-26 09:00:00', '2017-04-26 23:59:59');
INSERT INTO events VALUES (2, 'impra', '...', '2017-05-19 18:00:00', '2017-05-19 23:00:00');
INSERT INTO events VALUES (3, 'urodziny Boba', 'Bob u nas', '2017-05-02 18:00:00', '2017-05-02 23:00:00');
INSERT INTO events VALUES (4, 'koniec maja', 'koniec tego pieknego miesiaca', '2017-05-31 00:00:00', '2017-05-31 23:59:59');
INSERT INTO events VALUES (5, 'Przyjscie Boba', 'to nie jego urodziny a przyjscie', '2017-05-02 18:00:00', '2017-05-02 23:00:00');


--
-- Name: user_cal_serial; Type: SEQUENCE SET; Schema: public; Owner: ziom
--

SELECT pg_catalog.setval('user_cal_serial', 1, true);


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: ziom
--

INSERT INTO users VALUES (1, 'usertest', 'e0ebb67a5fd9066e5c2546c216ccc3b38a207d6a042eb30', 'admin@example.pl');


--
-- Name: events_pkey; Type: CONSTRAINT; Schema: public; Owner: ziom; Tablespace: 
--

ALTER TABLE ONLY events
    ADD CONSTRAINT events_pkey PRIMARY KEY (event_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

